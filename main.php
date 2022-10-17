<?php

interface IPet {
    public function getID();
    public function produceValuable();
    public static function getPronounce();
}

abstract class BasePet implements IPet {
    private $_ID = 0;

    public function __construct() {
        $this->_ID = uniqid( mt_rand() );
    }

    public function getID() {
        return ( $this->_ID );
    }

    abstract public function produceValuable();
    abstract static function getPronounce();
}

interface IValuable {
    public static function getPronounce();
    public static function getCountPronounce();
}

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
    public $count = 0;

    public function __construct( int $count ) {
        $this->count = $count;
    }

    public static function getPronounce() {
        return ( "Молоко" );
    }

    public static function getCountPronounce() {
        return ( "литров" );
    }
}

class Egg extends BaseValuable implements IValuable {
    public $count = 0;

    public function __construct( int $count ) {
        $this->count = $count;
    }

    public static function getPronounce() {
        return ( "Яйца" );
    }

    public static function getCountPronounce() {
        return ( "штук" );
    }
}

class Farm {
    private $_inhabitants = array();
    private $_storage     = array();
    private $_previous_processed_valuable = array();

    public function buyAnimal( string $pet_type_name, int $count = 1 ) {
        for ( $index = 0; $index < $count; $index++ ) {
            if ( isset( $this->_inhabitants[ $pet_type_name::getPronounce() ] ) ) {
                array_push(
                    $this->_inhabitants[ $pet_type_name::getPronounce() ],
                    new $pet_type_name()
                );

            } else {
                $this->_inhabitants[ $pet_type_name::getPronounce() ] = array( new $pet_type_name() );
            }
        }
    }

    public function addAnimal( object $animal, int $count = 1 ) {
        for ( $index = 0; $index < $count; $index++ ) {
            if ( isset( $this->_inhabitants[ $animal::getPronounce() ] ) ) {
                array_push(
                    $this->_inhabitants[ $animal::getPronounce() ],
                    new ( get_class( $animal ) )()
                );

            } else {
                $this->_inhabitants[ $animal::getPronounce() ] = array( new ( get_class( $animal ) )() );
            }
        }
    }

    public function addPet( object $pet ) {
        if ( isset( $this->_inhabitants[ $pet::getPronounce() ] ) ) {
            array_push(
                $this->_inhabitants[ $pet::getPronounce() ],
                $pet
            );

        } else {
            $this->_inhabitants[ $pet::getPronounce() ] = array( $pet );
        }
    }

    public function produceValuable() {
        foreach ( $this->_inhabitants as $inhabitants_type => $inhabitants ) {
            foreach ( $inhabitants as $inhabitant ) {
                $product = $inhabitant->produceValuable();

                if ( isset( $this->_storage[ $product::getPronounce() ] ) ) {
                    $this->_storage[ $product::getPronounce() ]->count += $product->count;

                } else {
                    $this->_storage[ $product::getPronounce() ] = $product;
                }
            }
        }
    }

    public function rememberProcessedValuable() {
        foreach ( $this->_storage as $valuable_name => $valuable ) {
            $this->_previous_processed_valuable[ $valuable_name ] = clone $valuable;
        }
    }

    public function getStorage() {
        return ( $this->_storage );
    }

    public function getRememberedStorage() {
        return ( $this->_previous_processed_valuable );
    }

    public function printInhabitantsCount() {
        print( "На ферме имеются:\n"  );

        foreach ( $this->_inhabitants as $pet_type_name => $inhabitants ) {
            printf(
                "%s в количестве %d\n",
                $pet_type_name,
                count( $inhabitants )
            );
        }
    }
}

$farm = new Farm();

$farm->rememberProcessedValuable();

print(
    "\033[1;33m" .
    "Летом, чтобы отдохнуть от городской суеты, вы поехали к дяде на ферму.\n" .
    "Через несколько дней отдых вам наскучил, и вы решили поупражняться в программировании.\n" .
    "Зайдя в хлев, где живут коровы и куры, и увидев как работает автоматический сборщик молока и яиц, \n".
    "вы решили описать его работу в парадигме ООП." .
    "\033[0m\n"
);

$farm->buyAnimal( "Cow", 10 );
$farm->buyAnimal( "Chicken", 20 );

$farm->printInhabitantsCount();

# First week
for ( $week_day_index = 0; $week_day_index < 7; $week_day_index++ ) {
    $farm->produceValuable();
}

print( "Продукция за неделю:\n" );

foreach ( $farm->getStorage() as $valuable_name => $valuable_instance ) {
    printf(
        "%s в количестве %d %s\n",
        $valuable_name,
        $valuable_instance->count,
        $valuable_instance::getCountPronounce()
    );
}

$farm->rememberProcessedValuable();

$farm->buyAnimal( "Chicken", 5 );
$farm->buyAnimal( "Cow", 1 );

$farm->printInhabitantsCount();

# Second week
for ( $week_day_index = 0; $week_day_index < 7; $week_day_index++ ) {
    $farm->produceValuable();
}

print( "Продукция за неделю:\n" );

foreach ( $farm->getStorage() as $valuable_name => $valuable_instance ) {
    printf(
        "%s в количестве %d %s\n",
        $valuable_name,
        $valuable_instance->count - $farm->getRememberedStorage()[ $valuable_name ]->count,
        $valuable_instance::getCountPronounce()
    );
}

$farm->rememberProcessedValuable();

print( "Итоговая продукция:\n" );

foreach ( $farm->getStorage() as $valuable_name => $valuable_instance ) {
    printf(
        "%s в количестве %d %s\n",
        $valuable_name,
        $valuable_instance->count,
        $valuable_instance::getCountPronounce()
    );
}
