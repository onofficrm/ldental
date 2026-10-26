// html-plugin.js
(function() {
    function htmlPlugin() {
        toastui.Editor.codeBlockManager.setReplacer('html', function(html) {
            const wrapperId = 'html_' + Math.random().toString(36).substr(2, 10);
            setTimeout(() => {
                const el = document.getElementById(wrapperId);
                if (el) {
                    el.innerHTML = html;
                }
            }, 0);
            return `<div id="${wrapperId}"></div>`;
        });
    }

    // 플러그인을 전역으로 노출하여 다른 곳에서 사용 가능하게 설정
    window.htmlPlugin = htmlPlugin;
})();
