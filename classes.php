<?php
class Message
{
    public string $message = "Hello world";

    public function sayIt(): string {
        return $this-> message;
    }
}

class Person1
{
    public string $name;
    public int $age;

    public function details(): string {
        return "My name is {$this->name}"
        . " and i am {$this->age} years old";
    }
}


class MyClass 
{
    const CONSTANT = 'const value';

    function showConstant() {
        echo self::CONSTANT . '\n';
    }
}

spl_autoload_register(function ($class_name) {
    include $class_name . ".php";
});

$obj = new MyClass1();
$obj2 = new MyClass2();

echo MyClass::CONSTANT . "\n";

$class = new MyClass();
echo $class::CONSTANT."\n";
// Class Points {
//     protected int $x;
//     protected int $y;

//     public function __construct(int $x, int $y = 0) : void {
//         $this->x = $x;
//         $this->y = $y;
//     }
// }

class MyDestructtableClass {
    function __construct() {
        print "in construtor";
    }

    function __destruct() {
        print "Destroying" . __CLASS__;
    }
}
$obj3 = new MyDestructtableClass();

class MyClass4
{
    public $public = "Public";
    protected $protected = "Proteced";
    private $private = "Private";

    function printHello(){
        echo $this->public;
        echo $this->protected;
        echo $this->private;
    }
}

$obj4 = new MyClass4;

echo $obj4->public; // The only one that will work since this is a pbulic property
// echo $obj->private; THESE 2 WONT WORK
// echo $obj->protected;
echo $obj4->printHello(); // This will print everything since its inside the class 



// instansera ett objekt
$object = new Person1();

// sett the properties of the object
$object->age = 23;
$object->name = "stonie";

// Call the mothod of the object 
echo $object->details();

//Debug and show the objects content 
var_dump($object);

//Sessions -------------------------------------------------------------------------

$_SESSION["dice"] = new Dice();

$_SESSION["dice"]->roll();

//Namespace ---------------------------------------------
//Den måste ligga överst
//namespace Stonie;

function hello(): string 
{
    return "hello";
}