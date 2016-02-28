<?php

namespace frontend\controllers;

use Yii;
use app\models\Army;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

//For RBAC
use yii\filters\AccessControl;
use common\models\User;

/**
 * ArmyController implements the CRUD actions for Army model.
 */
class ArmyController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete', 'attack'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::userIsBryant();
                        }
                    ],
                    [
                        'actions' => ['attack'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Army models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Army::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Army model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Army model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Army();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Army model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Army model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Army model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Army the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Army::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    //Attack a kingdom with the current user's army.
    public function actionAttack($target)
    {
        $userArmy = Army::find()->where(['user' => Yii::$app->user->identity->id])->one();
        $targetArmy = Army::find()->where(['user' => $target])->one();
        if($userArmy && $targetArmy)
        {
            $userLossesRanged = array();
            $targetLossesRanged = array();
            if($userArmy->getStats()['health'] && $targetArmy->getStats()['health'])
            {
                //Ranged Attack first.
                $userDamage = $userArmy->calculateDamageFromAttack($targetArmy, true);
                $targetDamage = $targetArmy->calculateDamageFromAttack($userArmy, true);
                //Track who was lost.
                $userLossesRanged = $userArmy->resolveDamage($userDamage);
                $targetLossesRanged = $targetArmy->resolveDamage($targetDamage);
            }
            $userLossesMelee = array();
            $targetLossesMelee = array();
            if($userArmy-getStats()['health'] && $targetArmy->getStats()['health'])
            {
                //Melee Attack second.
                $userDamage = $userArmy->calculateDamageFromAttack($targetArmy);
                $targetDamage = $targetArmy->calculateDamageFromAttack($userArmy);
                //Track who was lost.
                $userLossesMelee = $userArmy->resolveDamage($userDamage);
                $targetLossesMelee = $targetArmy->resolveDamage($targetDamage);
                //Send notification to target.
            }
            
            //Combine our arrays and display results.
            echo $userDamage . " - " . print_r($userLossesMelee);
            echo "<br/>";
            echo $targetDamage . " - " . print_r($targetLossesMelee);
        }
        else 
        {
            throw new \yii\base\Exception("Either user or target army does not exist. Error to be removed.");
        }
    }
}
