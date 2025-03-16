<?php

/**
 * Faz o resize do menu lateral
 *
 * @author Tiago Marques
 */
class AdminerResize
{
	function head() {
		// script para controlar a gestao de resize
		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"<?= Adminer\nonce() ?>></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"<?= Adminer\nonce() ?>></script>
		
		<script type="text/javascript"<?= Adminer\nonce() ?>>
			let resize_prev_x_pos = 0;
			let resizing = false;

			function resize(menu_width) {
				$("#menu").css("width", (menu_width) + "px");
				$("[data-resize]").css("left", menu_width + "px");
				$("#content").css("margin-left", (menu_width) + "px");
			}

			$(document).ready(function() {
				let menu_width = $("#menu").width();

				if ($.cookie("adminer_resize") !== undefined)
					menu_width = $.cookie("adminer_resize");

				$("[data-resize]").on("mousedown", function(e) {
					resize_prev_x_pos = e.pageX;
					resizing = true;
					$("body").css("cursor", "col-resize");
				});

				$("html").on("mousemove", function(e) {
					if (resizing) {
						e.preventDefault();
						menu_width = parseInt($("#menu").css("width"));
						let resize_left = parseInt($("[data-resize]").css("left"));
						let delta = (e.pageX - resize_prev_x_pos);
						let new_menu_width = menu_width + delta;

						if(new_menu_width > 231) {
							resize_prev_x_pos = e.pageX;
							resize(new_menu_width);

							// grava o valor do menu
							menu_width = new_menu_width;
						}
					}
				}).on("mouseup", function(e) {
					resizing = false;
					$("body").css("cursor", "default");

					// salva o valor novo em cookie
					$.cookie("adminer_resize", menu_width, {expires: 999999, path: "/"});
				});

				resize(menu_width);
			});
		</script>

		<style type="text/css">
			[data-resize] { display: block; position: fixed; top: 40px; left: 250px; width: 4px; min-height: 100%; background-color: #65ADC3; cursor: col-resize; }
		</style>
		<?php
	}

	/**
	 * Coloca a linha de redimensionamento
	 *
	 * @param string $missing
	 */
	function navigation(string $missing) {
		echo "<span data-resize></span>";
	}
}
