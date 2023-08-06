<?php

interface IPet {
    public function getID();
    public function produceValuable();
    public static function getPronounce();
} //end interface IPet

abstract class BasePet implements IPet {
    private $_PetId = 0;

    public function __construct() {
        $this->_PetId = uniqid( mt_rand() );
    }

    public function getID() {
        return ( $this->_PetId );
    }

    abstract public function produceValuable();
    abstract public static function getPronounce();
}

interface IValuable {
    public static function getPronounce();
    public static function getCountPronounce();
} //end interface IValuable

abstract class BaseValuable implements IValuable {
    abstract public static function getPronounce();
    abstract public static function getCountPronounce();
}

class Cow extends BasePet implements IPet {
    public function __construct() {
        parent::__construct();

        print( "Вы приобрели корову!\n" );
    }

    public function produceValuable() {
        return ( new Milk( mt_rand( 8, 12 ) ) );
    }

    public static function getPronounce() {
        return ( "Корова" );
    }
}

class Chicken extends BasePet implements IPet {
    public function __construct() {
        parent::__construct();

        print( "Вы приобрели курицу!\n" );
    }

    public function produceValuable() {
        return ( new Egg( mt_rand( 0, 1 ) ) );
    }

    public static function getPronounce() {
        return ( "Курица" );
    }
}

class Milk extends BaseValuable implements IValuable {
    public $Count = 0;

    public function __construct( int $Count ) {
        $this->Count = $Count;
    }

    public static function getPronounce() {
        return ( "Молоко" );
    }

    public static function getCountPronounce() {
        return ( "литров" );
    }
}

class Egg extends BaseValuable implements IValuable {
    public $Count = 0;

    public function __construct( int $Count ) {
        $this->Count = $Count;
    }

    public static function getPronounce() {
        return ( "Яйца" );
    }

    public static function getCountPronounce() {
        return ( "штук" );
    }
}

class Farm {
    private $_Inhabitants = [];
    private $_Storage     = [];
    private $_PreviousProcessedValuable = [];

    public function buyAnimal( string $pet_type_name, int $Count = 1 ) {
        for ( $index = 0; $index < $Count; $index++ ) {
            if ( isset( $this->_Inhabitants[ $pet_type_name::getPronounce() ] )
                === true ) {
                array_push(
                    $this->_Inhabitants[ $pet_type_name::getPronounce() ],
                    new $pet_type_name()
                );

            } else {
                $this->_Inhabitants[ $pet_type_name::getPronounce() ]
                    = [ new $pet_type_name() ];
            }
        }
    }

    public function addAnimal( object $animal, int $Count = 1 ) {
        for ( $index = 0; $index < $Count; $index++ ) {
            if ( isset( $this->_Inhabitants[ $animal::getPronounce() ] )
                === true ) {
                array_push(
                    $this->_Inhabitants[ $animal::getPronounce() ],
                    new ( get_class( $animal ) )()
                );

            } else {
                $this->_Inhabitants[ $animal::getPronounce() ]
                    = [ new ( get_class( $animal ) )() ];
            }
        }
    }

    public function addPet( object $pet ) {
        if ( isset( $this->_Inhabitants[ $pet::getPronounce() ] )
            === true ) {
            array_push(
                $this->_Inhabitants[ $pet::getPronounce() ],
                $pet
            );

        } else {
            $this->_Inhabitants[ $pet::getPronounce() ] = [ $pet ];
        }
    }

    public function produceValuable() {
        // Unused $inhabitants_type
        foreach ( $this->_Inhabitants as $inhabitants_type => $inhabitants ) {
            foreach ( $inhabitants as $inhabitant ) {
                $product = $inhabitant->produceValuable();

                if ( isset( $this->_Storage[ $product::getPronounce() ] ) ) {
                    $this->_Storage[ $product::getPronounce() ]->Count
                        += $product->Count;

                } else {
                    $this->_Storage[ $product::getPronounce() ] = $product;
                }
            }
        }
    }

    public function rememberProcessedValuable() {
        foreach ( $this->_Storage as $valuable_name => $valuable ) {
            $this->_PreviousProcessedValuable[ $valuable_name ] = clone $valuable;
        }
    }

    public function getStorage() {
        return ( $this->_Storage );
    }

    public function getRememberedStorage() {
        return ( $this->_PreviousProcessedValuable );
    }

    public function printInhabitantsCount() {
        print( "На ферме имеются:\n"  );

        foreach ( $this->_Inhabitants as $pet_type_name => $inhabitants ) {
            printf(
                "%s в количестве %d\n",
                $pet_type_name,
                Count( $inhabitants )
            );
        }
    }
}

$farm = new Farm();

$farm->rememberProcessedValuable();

print(
    "\033[1;33m" .
    "Летом, чтобы отдохнуть от городской суеты, " .
    "вы поехали к дяде на ферму.\n" .
    "Через несколько дней отдых вам наскучил, " .
    "и вы решили поупражняться в программировании.\n" .
    "Зайдя в хлев, где живут коровы и куры, " .
    "и увидев как работает автоматический сборщик молока и яиц, \n" .
    "вы решили описать его работу в парадигме ООП." .
    "\033[0m\n"
);

$farm->buyAnimal( "Cow", 10 );
$farm->buyAnimal( "Chicken", 20 );

$farm->printInhabitantsCount();

// First week
for ( $week_day_index = 0; $week_day_index < 7; $week_day_index++ ) {
    $farm->produceValuable();
}

print( "Продукция за неделю:\n" );

foreach ( $farm->getStorage() as $valuable_name => $valuable_instance ) {
    printf(
        "%s в количестве %d %s\n",
        $valuable_name,
        $valuable_instance->Count,
        $valuable_instance::getCountPronounce()
    );
}

$farm->rememberProcessedValuable();

$farm->buyAnimal( "Chicken", 5 );
$farm->buyAnimal( "Cow", 1 );

$farm->printInhabitantsCount();

// Second week
for ( $week_day_index = 0; $week_day_index < 7; $week_day_index++ ) {
    $farm->produceValuable();
}

print( "Продукция за неделю:\n" );

foreach ( $farm->getStorage() as $valuable_name => $valuable_instance ) {
    printf(
        "%s в количестве %d %s\n",
        $valuable_name,
        ( $valuable_instance->Count -
            $farm->getRememberedStorage()[ $valuable_name ]->Count ),
        $valuable_instance::getCountPronounce()
    );
}

$farm->rememberProcessedValuable();

print( "Итоговая продукция:\n" );

foreach ( $farm->getStorage() as $valuable_name => $valuable_instance ) {
    printf(
        "%s в количестве %d %s\n",
        $valuable_name,
        $valuable_instance->Count,
        $valuable_instance::getCountPronounce()
    );
}
