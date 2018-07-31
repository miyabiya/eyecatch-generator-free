<?php
/*
Plugin Name: eyecatch-generator-free
Description: 無料版アイキャッチジェネレーターです。投稿画面でアイキャッチを作成できるようになります
Author: Mitsumasa Yamaga
Version: 0.1
Author URI: https://www.facebook.com/miyabi.yc
*/
 
/// 投稿画面　アイキャッチgenerater
/**
 * 投稿画面の右サイドに枠(メタボックス)を追加します。
 */
function add_side_meta_box_free_generator() {
	if (has_post_thumbnail()) {
		add_meta_box( 'free_eyecatch_gene', '無料版アイキャッチジェネレーター', 'add_side_free_gene_meta_box_callback', 'post', 'side', 'default' );
	}
}

/**
 * 枠の内容を出力します。
 */
function add_side_free_gene_meta_box_callback() { ?>
	<canvas width="1000" height="750" id="generator-view-free"></canvas>
    <input type="text" class="generator-title-free" placeholder="一つ目のタイトルを入れてください" maxlength="15" style="width:100%">
    <p style="color: red; font-size: 12px; margin-top: 0;">※一つ目のタイトルは（全角）15文字までです</p>
    <input type="text" class="generator-title-free2" placeholder="二つ目のタイトルを入れてください" maxlength="30" style="width:100%">
    <p style="color: red; font-size: 12px; margin-top: 0;">※二つ目のタイトルは（全角）30文字までです(15文字を越えると自動で改行してくれます)</p>
    <button type="button" class="generator-btn-free">作成</button>
    <img src="" alt="" class="generated-img">
<?php }
add_action( 'add_meta_boxes', 'add_side_meta_box_free_generator' );

add_action( 'edit_form_advanced', 'disp_eyecatch_free_generator' );
function disp_eyecatch_free_generator($post){ ?>
    <?php
    $image_id = get_post_thumbnail_id();
    $image_url = wp_get_attachment_image_src($image_id, true);
    ?>
    <script type="text/javascript">
    var isStore = false;
    var canvasFree = document.querySelector("#generator-view-free");
    console.log(canvasFree);
    var contextFree = canvasFree.getContext("2d");
    var imgFree = new Image();
    var imgFree2 = new Image();
    function draw() {
        console.log(canvasFree);
        imgFree.src = "<?php echo $image_url[0]; ?>";
        console.log(imgFree);
        imgFree.onload = function() {
            console.log(this.width, this.height);
            // clip case
            // height > width
            if (this.height > this.width) {
                contextFree.drawImage(this, 0, 0, 1000, (this.height / this.width) * 1000);
            } else {
                contextFree.drawImage(this, 0, 0, (this.width / this.height) * 750, 750);
            }
            contextFree.fillStyle = 'rgba(0, 0, 0, 0.4)';
            contextFree.fillRect(0, 0, 1000, 750);
            contextFree.fillStyle = 'rgba(255, 255, 255, 0.8)';
            contextFree.fillRect(0, 550, 1000, 200);
            imgFree2.src = "<?php echo( get_header_image() ); ?>";
            console.log("<?php echo( get_header_image() ); ?>");
            imgFree2.onload = function() {
                const aspect = this.height / this.width;
                const baseImgWidth = 1000 / 3;
                console.log(baseImgWidth * aspect);
                // basicaly, img width is one per three by canvasFree width;
                contextFree.drawImage(this, 500 - baseImgWidth/2, 650 - (baseImgWidth * aspect) / 2, baseImgWidth, baseImgWidth * aspect);
            }
        }
    };
    function strIns(str, idx, val){
        var res = str.slice(0, idx) + val + str.slice(idx);
        return res;
    };

    function fillTextLine(ctx, text, x, y) {
        let textList = text.split('\n');
        let lineHeight = ctx.measureText("あ").width;
        textList.forEach(function(text, i) {
            if (i === 0) {
                ctx.fillText(text, x, y+lineHeight*i);
            } else {
                ctx.fillText(text, (canvasFree.width / 2 - (text.length / 2 * 60)), y+lineHeight*i + 75);
            }
        });
    };
    $(function(){
      draw();
      $('.generator-btn-free').click(function() {
          let textFree = $('.generator-title-free').val();
          let textFree2 = $('.generator-title-free2').val();
          if (textFree2.length > 15) {
              console.log('into state');
              textFree2 = strIns(textFree2, 15, '\n');
          }
          console.log('click eve', textFree, textFree2);
          if (imgFree.height > imgFree.width) {
              contextFree.drawImage(imgFree, 0, 0, 1000, (imgFree.height / imgFree.width) * 1000);
          } else {
              contextFree.drawImage(imgFree, 0, 0, (imgFree.width / imgFree.height) * 750, 750);
          }
          contextFree.fillStyle = 'rgba(0, 0, 0, 0.4)';
          contextFree.fillRect(0, 0, 1000, 750);
          contextFree.fillStyle = 'rgba(255, 255, 255, 0.8)';
          contextFree.fillRect(0, 550, 1000, 200);
          const aspect = imgFree2.height / imgFree2.width;
          const baseImgWidth = 1000 / 3;
          // basicaly, img width is one per three by canvasFree width;
          contextFree.drawImage(imgFree2, 500 - baseImgWidth/2, 650 - (baseImgWidth * aspect) / 2, baseImgWidth, baseImgWidth * aspect);
          contextFree.font = "normal 60px 'Arial'";
          contextFree.fillStyle = "white";
          contextFree.fillText(
            textFree,
            (canvasFree.width / 2 - (textFree.length / 2 * 60)),
            165
          );
          const x = textFree2.length > 15 ? (canvasFree.width / 2 - 450) : (canvasFree.width / 2 - (textFree2.length / 2 * 12));
          fillTextLine(contextFree, textFree2, x, 300);
      });
    });
    </script>
    <style>
    canvas#generator-view-free {
        width: 100%;
    }
    </style>
<?php }