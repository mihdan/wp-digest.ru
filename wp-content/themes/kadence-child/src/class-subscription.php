<?php
/**
 *
 */

namespace Mihdan\Kadence_Child;

class Subscription {
    public function setup_hooks() {
	    add_action(
		    'kadence_single_after_inner_content',
		    function () {
			    if ( ! is_singular( 'post' ) ) {
				    return;
			    }
			    ?>
                <div class="subscribe-widget">
                    <h2 class="subscribe-widget__heading">Подпишись на рассылку!</h2>
                    <p>Нравится эта статья? Такой годноты у нас хоть отбавляй. Подписывайся на обновления сайта по Email.</p>
                    <table>
                        <tr>
                            <td>
                                &mdash; Важные новости <br>
                                &mdash; События в мире WordPress <br>
                                &mdash; Статьи известных авторов <br>
                            </td>
                            <td>
                                &mdash; Обзоры из первых уст <br>
                                &mdash; Руководства для новичков <br>
                                &mdash; Сравнения тем и плагинов <br>
                            </td>
                        </tr>
                    </table>
                    <form class="subscribe-widget__form" action="https://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('https://feedburner.google.com/fb/a/mailverify?uri=wp-digest-com', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
                        <input type="hidden" value="wp-digest-com" name="uri"/>
                        <input type="hidden" name="loc" value="ru_RU"/>
                        <input class="subscribe-widget__email" type="email" placeholder="Введите ваш Email" name="email"/>
                        <input class="subscribe-widget__button" type="submit" value="Подписаться" />
                    </form>
                </div>
			    <?php
		    },
		    0
	    );
    }
}
