<?php


interface IMyStorage
{
    public function persist($object);
    public function flush();
}

interface IMyValidator
{
    public function validate($object);
}


class ProductManager
{
    /**
     * @var IMyStorage
    */
    private $entityManager;

    /**
     * @var IMyValidator
    */
    private $validator;

    public function __construct(IMyStorage $em, IMyValidator $validator)
    {
        $this->entityManager = $em;
        $this->validator = $validator;
    }

    public function save(Product $product)
    {
        if ($this->validator->validate($product) == 0) {
            return false;
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}








































die();


class A {
    private $var;
    public function __construct($v)
    {
        $this->var = $v;
    }

    public function getVar() { return $this->var; }
}

$a = [
    new A(2),
    new A(5),
    new A(1)
];

///////////////////////

$b = $a;

for ($i = 0; $i < count($b); $i++)
{
    for ($j = 0; $j < count($b); $j++)
    {
        if ($b[$i]->getVar() < $b[$j]->getVar())
        {
            $temp = $b[$i];
            $b[$i] = $b[$j];
            $b[$j] = $temp;
        }
    }
}

echo '<pre>';
print_r($b);