<?php

namespace webvimark\components;

use Yii;
use webvimark\modules\UserManagement\components\GhostAccessControl;
use yii\web\Controller;

class BaseController extends Controller {

	/**
	 * @return array
	 */
	public function behaviors() {

        ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

		return [
			'ghost-access' => ['class' => GhostAccessControl::class], 
		];
	}

	/**
	 * Render ajax or usual depends on request
	 *
	 * @param string $view
	 * @param array $params
	 *
	 * @return string|\yii\web\Response
	 */
	protected function renderIsAjax($view, $params = []) {
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax($view, $params);
		}
		else {
			return $this->render($view, $params);
		}
	}
}