function kakaolink_send(text, url, img)
{
    // 카카오톡 링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
    Kakao.Link.sendTalkLink({
        label: String(text),
        image: {
            src: img,
            width: '300',
            height: '300'

        },
        webButton: {
            text: '초특가 상품 보러가기',
            url: url // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.,

        }
    });
}