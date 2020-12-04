<?php
/**
 *
 */

namespace Mihdan\WP_Digest;

add_action(
	'wp_footer',
	function () {
		//if ( is_singular( 'post' ) || is_home() ) {
			?>
			<script type="text/javascript">
                (function (a, b, c, d, e, f, g, h) {
                    g = b.createElement(c);
                    g.src = d;
                    g.type = "application/javascript";
                    g.async = !0;
                    h = b.getElementsByTagName(c)[0];
                    h.parentNode.insertBefore(g, h);
                    a[f] = [];
                    a[e] = function () {
                        a[f].push(Array.prototype.slice.apply(arguments));
                    }
                })(window, document, "script", (document.location.protocol ===
                "https:" ? "https:" : "http:") + "//cdn01.nativeroll.tv/js/seedr-player.min.js",
                    "SeedrPlayer", "seedrInit");
			</script>
			<script>
                SeedrPlayer({
                    container: '.single-entry',
                    article: '.single-content',
                    desiredOffset: 50,
                    gid: '5fc8ba6708780f6bba34fdfa',
                    category: 'Новости',
                    onError: function(e) {
                        console.log( e );
                    }
                });
			</script>
			<?php
		//}
	}
);
