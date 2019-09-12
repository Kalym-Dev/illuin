<?php


namespace core; //задаем пространство имен


abstract class Controller //задаем абстрактный класс. Он абстрактный потому что нам нужно чтобы от него наследовались другие классы, но нам не нужно чтобы его экземпляры создавались.

{
    protected $route; //маршут
    protected $model; //свойство в котором хранится объект модели
    protected $view; //свойство в котором хранится объект вида

    public function __construct(array $route){ //передача маршута
       $this->route = $route; //сохранение маршута в свойство класса
       $this->model = $this->loadModel($this->route['controller']); // в свойство класса model передается ссылка на объетк модели
        $this->view = new View($this->route); //сохраняем в свойство view объект класса View, передавая ему маршуты
    }

   private function loadModel($model_name){ //подключение модели
       $path_to_model = 'models\\' . ucfirst($model_name); //путь до модели
       if (class_exists($path_to_model)) { //если такой класс существует, то
           return new $path_to_model; //возращаем объект этого класса (модели)
       }
   }

}