<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function editor_html($id, $content, $is_dhtml_editor=true)
{
    global $config, $w, $board, $write;
    global $editor_width, $editor_height;
    static $js = true;

    $width  = isset($editor_width)  ? $editor_width  : "100%";
    $height = isset($editor_height) ? $editor_height : "400px";

    $editor_url = G5_EDITOR_URL.'/'.$config['cf_editor'];
    $html = "";

    if ($is_dhtml_editor) {
        if ($js) {
            // TOAST UI Editor CSS
            $html .= '<link rel="stylesheet" href="'. $editor_url . '/css/toastui-editor.min.css" />';
            $html .= '<link rel="stylesheet" href="'. $editor_url . '/plugins/color-syntax/tui-color-picker.min.css" />';
            $html .= '<link rel="stylesheet" href="'. $editor_url . '/plugins/color-syntax/toastui-editor-plugin-color-syntax.min.css" />';

            // TOAST UI Editor JS
            $html .= '<script src="'. $editor_url . '/plugins/color-syntax/tui-color-picker.min.js"></script>';
            $html .= '<script src="'. $editor_url . '/js/toastui-editor-all.min.js"></script>';
            $html .= '<script src="'. $editor_url . '/js/ko-kr.js"></script>';
            $html .= '<script src="'. $editor_url . '/plugins/color-syntax/toastui-editor-plugin-color-syntax.min.js"></script>';
            $html .= '<script src="'. $editor_url . '/plugins/html-plugin/html-plugin.js"></script>';

            $js = false;
        }

        $html .= '<div id="editor_'.$id.'"></div>';
        $html .= '<textarea name="'.$id.'" id="'.$id.'" style="display:none;">'.$content.'</textarea>';
        $html .= '
        <script>
            var { Editor } = toastui;
            var { colorSyntax } = Editor.plugin;

            var editor_'.$id.' = new Editor({
                el: document.querySelector("#editor_'.$id.'"),
                height: "'.$height.'",
                initialValue: document.getElementById("'.$id.'").value,
                initialEditType: "wysiwyg",
				hideModeSwitch: true,
                previewStyle: "vertical",
                extendedAutolinks: true,
                language: "ko-KR",
                toolbarItems: [
                    ["heading", "bold", "italic", "strike"],
                    ["quote"],
                    ["ul", "ol", "task", "indent", "outdent"],
                    ["table", "image", "link"],
                    ["code", "codeblock"],
                    ["scrollSync"],
                ],
                plugins: [colorSyntax],
                hooks: {
                    addImageBlobHook: (blob, callback) => {
                        const formData = new FormData();
                        formData.append("file", blob);

                        fetch("'.$editor_url. '/upload/upload.php", { // 서버의 업로드 스크립트 URL
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                callback(data.url, "alt text"); // 이미지 URL을 콜백으로 전달
                            } else {
                                alert(data.error); // 에러 메시지 표시
                            }
                        })
                        .catch(error => {
                            console.error("업로드 실패:", error);
                        });
                    }
                }
            });
        </script>
        ';
    } else {
        $html .= '<textarea id="'.$id.'" name="'.$id.'" style="width:'.$width.';height:'.$height.';" maxlength="65536">'.$content.'</textarea>';
    }
    return $html;
}

function get_editor_js($id, $is_dhtml_editor=true)
{
    if ($is_dhtml_editor) {
        return "document.getElementById('".$id."').value = editor_".$id.".getHTML();\n";
    } else {
        return "var ".$id."_editor = document.getElementById('".$id."');\n";
    }
}

function chk_editor_js($id, $is_dhtml_editor=true)
{
    if ($is_dhtml_editor) {
        return "if (editor_".$id.".getMarkdown().trim() === '') { alert('내용을 입력해 주십시오.'); editor_".$id.".focus(); return false; }\n";
    } else {
        return "if (!".$id."_editor.value) { alert('내용을 입력해 주십시오.'); ".$id."_editor.focus(); return false; }\n";
    }
}