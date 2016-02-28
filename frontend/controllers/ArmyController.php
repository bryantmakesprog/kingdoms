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

use app\models\UnitConnections;
use app\models\Unit;

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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'attack', 'status', 'roster', 'recruit'],
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
                        'actions' => ['attack', 'status', 'roster', 'recruit'],
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
    
    //Overall army view.
    public function actionStatus()
    {
        $army = Army::find()->where(['user' => Yii::$app->user->identity->id])->one();
        return $this->render('status', ['model' => $army,]);
    }
    
    //All army units.
    public function actionRoster()
    {
        $unitConnection = new UnitConnections();
        if ($unitConnection->load(Yii::$app->request->post()))
        {
//TODO - Subtract money------------------------------------------------------------------------------------------------------------------------------------
            $message = "";
            $messageType = "";
            $existingConnection = UnitConnections::find()->where(['unit' => $unitConnection->unit, 'army' => $unitConnection->army])->one();
            if($existingConnection && ($existingConnection->count >= $unitConnection->count))
            {
                $existingConnection->count -= $unitConnection->count;
                $existingConnection->save();
                $message = "You successfully removed " . $unitConnection->count . " " . Unit::findOne($unitConnection->unit)->name . "!";
                $messageType = "success";
                if($existingConnection->count == 0)
                {
                    $existingConnection->delete();
                }
            }
            else
            {    
                $message = "You don't own " . $unitConnection->count . " " . Unit::findOne($unitConnection->unit)->name . "!";
                $messageType = "failure";
            }
            return $this->render('roster', ['message' => $message, 'messageType' => $messageType]);
        }
        $army = Army::find()->where(['user' => Yii::$app->user->identity->id])->one();
        return $this->render('roster', ['model' => $army, 'message' => "", 'messageType' => ""]);
    }
    
    //All recruitable units.
    public function actionRecruit()
    {
        $unitConnection = new UnitConnections();
        if ($unitConnection->load(Yii::$app->request->post()))
        {
//TODO - Subtract money------------------------------------------------------------------------------------------------------------------------------------
            $message = "";
            $messageType = "";
            $sufficientFunds = true;
            if($sufficientFunds)
            {
                $existingConnection = UnitConnections::find()->where(['unit' => $unitConnection->unit, 'army' => $unitConnection->army])->one();
                if($existingConnection)
                {
                    $existingConnection->count += $unitConnection->count;
                    $existingConnection->save();
                }
                else
                    $unitConnection->save();
                $message = "You successfully purchased " . $unitConnection->count . " " . Unit::findOne($unitConnection->unit)->name . "!";
                $messageType = "success";
            }
            else
            {
                $message = "Insufficient funds!";
                $messageType = "failure";
            }
            return $this->render('recruit', ['message' => $message, 'messageType' => $messageType]);
        }
        $army = Army::find()->where(['user' => Yii::$app->user->identity->id])->one();
        return $this->render('recruit', ['model' => $army, 'message' => "", 'messageType' => ""]);
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
            $userHealth = $userArmy->getStats()['health'];
            $targetHealth = $targetArmy->getStats()['health'];
            $unableToAttack = false;
            $raid = false;
            if($userHealth && $targetHealth)
            {
                //Ranged Attack first.
                $userDamage = $userArmy->calculateDamageFromAttack($targetArmy, true);
                $targetDamage = $targetArmy->calculateDamageFromAttack($userArmy, true);
                //Track who was lost.
                $userLossesRanged = $userArmy->resolveDamage($userDamage);
                $targetLossesRanged = $targetArmy->resolveDamage($targetDamage);
            }
            else
            {
                if(!$userHealth)
                    $unableToAttack = true;
                if(!$targetHealth && $userHealth) //If the user has health left, this becomes a raid.
                    $raid = true;
            }
            $userHealth = $userArmy->getStats()['health'];
            $targetHealth = $targetArmy->getStats()['health'];
            $userWipe = false;
            $targetWipe = false;
            $userLossesMelee = array();
            $targetLossesMelee = array();
            if($userHealth && $targetHealth)
            {
                //Melee Attack second.
                $userDamage = $userArmy->calculateDamageFromAttack($targetArmy);
                $targetDamage = $targetArmy->calculateDamageFromAttack($userArmy);
                //Track who was lost.
                $userLossesMelee = $userArmy->resolveDamage($userDamage);
                $targetLossesMelee = $targetArmy->resolveDamage($targetDamage);
                //Send notification to target.
            }
            $userHealth = $userArmy->getStats()['health'];
            $targetHealth = $targetArmy->getStats()['health'];
            if(!($userHealth && $targetHealth))
            {
                if(!$userHealth && !$unableToAttack)
                    $userWipe = true;
                if(!$targetHealth && !$raid)
                {
                    $targetWipe = true;
                    if($userHealth) //If the user still has health, this becomes a raid.
                        $raid = true;
                }
            }
            //Combine our loss arrays.
            $userLosses = $this->sumLossArrays($userLossesRanged, $userLossesMelee);
            $targetLosses = $this->sumLossArrays($targetLossesRanged, $targetLossesMelee);
            //Display results.
            if(!$unableToAttack)
            {
                return $this->render('battle', [
                    'userLosses' => $userLosses, 
                    'targetLosses' => $targetLosses, 
                    'target' => $target,
                    'userWipe' => $userWipe,
                    'targetWipe' => $targetWipe,
                    'raid' => $raid,
                    ]);
            }
        }
        else 
        {
            throw new \yii\base\Exception("Either user or target army does not exist. Error to be removed.");
        }
    }
    
    private function sumLossArrays($lossesRanged, $lossesMelee)
    {
        $totalLosses = $lossesMelee;
        foreach($lossesRanged as $key => $value)
        {
            if(array_key_exists($key, $totalLosses))
            {
                $totalLosses[$key] += $value;
            }
            else
            {
                $totalLosses[$key] = $value;
            }
        }
        return $totalLosses;
    }
}
