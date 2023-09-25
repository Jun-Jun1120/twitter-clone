$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    const updateButtonState = (button, isLiked) => {
        if (isLiked) {
            button.removeClass("far").addClass("fas liked");
        }
        if (!isLiked) {
            button.removeClass("fas liked").addClass("far");
        }
    };

    $(".like-button").each(function () {
        const button = $(this);
        const tweetId = button.data("tweet-id");

        // ページ読み込み時に各ツイートがいいねされているか確認
        $.get(`/tweets/${tweetId}/returnLikeStatus`, function (data) {
            updateButtonState(button, data.isLiked);
        });

        // いいねボタンがクリックされたとき処理
        button.on("click", function () {
            $.get(`/tweets/${tweetId}/returnLikeStatus`, function (data) {
                if (data.isLiked) {
                    // すでにいいねされている場合、いいねを取り消すリクエストを送信
                    $.ajax({
                        url: `/tweets/${tweetId}/unlike`,
                        type: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        success: function (data) {
                            updateButtonState(button, data.isLiked);

                            // いいね数を更新
                            const likeCountElement = button.next();
                            likeCountElement.text(data.likeCount);
                        },
                    });
                }
                if (!data.isLiked) {
                    // いいねされていない場合、いいねを追加するリクエストを送信
                    $.post(
                        `/tweets/${tweetId}/like`,
                        {
                            _token: csrfToken,
                        },
                        function (data) {
                            updateButtonState(button, data.isLiked);

                            // いいね数を更新
                            const likeCountElement = button.next();
                            likeCountElement.text(data.likeCount);
                        }
                    );
                }
            });
        });
    });
});
