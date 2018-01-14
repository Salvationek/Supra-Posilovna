<?php
/**
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */


namespace app\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * Hlavní aplikační asset. Stará se o správné nastavení css stylů, podle přihlášeného uživatele.
 *
 * @see https://github.com/Salvationek/Supra-Posilovna
 * @author Martin Mašata <masatma1@fel.cvut.cz>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init() {
        if (isset(Yii::$app->user->identity->theme)) {
            $this->css = ['css/' . Yii::$app->user->identity->theme->value];
        }
        else {
            $this->css = ['css/site.css'];
        }
    }
}
