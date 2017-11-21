<?php

namespace App;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


function printIt($str) {
    if(php_sapi_name() === 'cli') {
        // echo $str;
    }
    else {
        $str = str_replace("\n", "<br>\n", $str);
        $str = str_replace("\t", str_repeat("&nbsp;", 4), $str);
        // echo $str;
    }
}

/**
* 
*/
class BaseSchemaInfo
{
    /**
     * 
     *
     * @
     */
    protected static $tableName;
    
    /**
     * 
     *
     * @var Blueprint $table
     */
    protected static $table;

    /**
     * A multidimensional associative array that holds the column descriptors
     *
     * @var array
     */
    protected static $schema;

    /**
     * 
     *
     * @var array of Fluent columns
     */
    protected static $columns;

    /**
     * 
     *
     * @
     */
    protected static $foreignKeys;


    /**
     * 
     *
     * @var array
     */
    protected static $foreignDisplayColumns;

    /**
     * columns to exclude from the form
     *
     * @var array
     */
    protected static $except;


    function __construct($tableName, $table = null)
    {
        static::$tableName = $tableName;

        if(is_null($table)) static::$table = new Blueprint($tableName);
        else static::$table = $table;

        static::runMigration($tableName);
        static::setForeignKeys();
    }

    /**
     * Returns column descriptor array 
     *
     * @param  string
     * @return array
     */
    public static function getSchema($tableName) {
        return static::$schema[$tableName];
    }

    /**
     * Returns an associative array as "column name" => "column type" pairs 
     *
     * @param  string
     * @return array
     */
    public static function getTableNames() {
    
        $tables = static::$schema;
        
        $arr = [];

        foreach($tables as $tableName=>$valuesArr) {
            $arr[] = $tableName;
        }

        return $arr;
    }


    /**
     * Returns an associative array as "column name" => "column type" pairs 
     *
     * @param  string
     * @return array
     */
    public static function getColumn($name) {
    
        return static::$columns->where('name', $name);
    }


    /**
     * Returns an associative array as "column name" => "column type" pairs 
     *
     * @param  string
     * @return array
     */
    public static function getColumns() {
    
        return static::$columns;

        // $columns = static::$schema[$tableName];
        
        // foreach($columns as $column) {
        //     unset($type);
        //     unset($options);
            
        //     $name = $column[0];

        //     $size = count($column);

        //     $type = ($size > 1 ) ? $column[1] : '';

        //     if( $type == 'string') {
        //         $arr[$name] = 'varchar(255)'; 
        //     }
        //     elseif(strstr($type, 'string|') != '') {
        //         $typeArr = explode("|", $type);
        //         $type = 'string';
        //         $length = $typeArr[1];

        //         $arr[$name] = "varchar($length)";
        //     }
        //     if( $type == 'increments' ) {
        //         $arr[$name] = 'integer'; 
        //     }                    
        //     elseif( $name == 'rememberToken' ) {
        //         $arr['_token'] = 'varchar(100)'; 
        //     }                    
        //     elseif( $name == 'timestamps'    ) {
        //         $arr['created_at'] = 'timestamp(0) without time zone'; 
        //         $arr['updated_at'] = 'timestamp(0) without time zone'; 
        //     }
        //     elseif( $type == 'foreign') {
        //         // ignore
        //     }
        //     else {
        //         $arr[$name] = $type;
        //     }
        // }

        // return $arr;
    }

     /**
     * Returns the foreign key information for the given table
     *
     * @param  string
     * @return array
     */
    public static function isRequired($name) {

        dd(static::$columns);

        return static::$foreignKeys;
    }
        
     /**
     * Returns the foreign key information for the given table
     *
     * @param  string
     * @return array
     */
    public static function getForeignKeyValues($name) {
        
        // dd(static::$foreignKeys);

        $collection = static::$foreignKeys->where('name', $name);

        if(count($collection) > 0) {

            $fkey = $collection->first();
            
            $references = $fkey['references'];
            $on = $fkey['on'];

            $find = [];

            $class = preg_replace('/^(.*)s$/', "$1", $on);
            $class = ucwords(preg_replace('/_/', " ", $class));
            $class = preg_replace('/ /', '', $class);

            // echo "$name references $references on $on. Model = \\App\\$class" ."<br>\n";

            $columnsToDisplay = static::$foreignDisplayColumns[$name];
            $columnsToDisplay[] = 'id';            

            $foreignValues = call_user_func("\\App\\$class::select", $columnsToDisplay)->get(); //->pluck('id', $references);
            
            
            $foreignValues = $foreignValues->map(function($role){

                $id     = $role->id;
                $name   = $role->name;
                $ts     = $role->created_at;
                $arr['id'] = $id;
                $arr['value'] = $name;
                if(!empty($ts)) $arr['value'] .= " - " . $ts;
                return $arr;
            });

            // dump($foreignValues->pluck('value', 'id'));

            return $foreignValues->pluck('value', 'id'); 
        }
    }
        
    /**
     * Formats the foreign key array
     *
     * @param  string
     * @return array
     */
 
    public static function setForeignKeys() {
        
        $columns = static::$table->getCommands();

        $arr = [];

        foreach($columns as $column) {
            $arr[] = $column->toArray();       
        }

        $foreignKeys = collect($arr)->where('name','foreign');
            
        $keys = [];

        foreach ($foreignKeys as $foreignKey) {
 
            $columnId = $foreignKey['columns'][0];

            $foreignKey['name'] = $columnId;
            
            unset($foreignKey['columns']);
            unset($foreignKey['algorithm']);

            $keys[] = $foreignKey;
        }

        static::$foreignKeys = collect($keys);
    }

    /**
     * 
     *
     * @
     */
    public static function toHtmlForm($except, $hidden, $foreignDisplayColumns) {

        static::$except = $except;
        static::$foreignDisplayColumns = $foreignDisplayColumns;

        $laravelToPostGreSQL = [
            'longstring'=> 'textarea',
            'string'    => 'text',
            'decimal'   => 'text',
            'text'      => 'text',
            'date'      => 'date',
            'integer'   => 'text',
            'boolean'   => 'checkbox',
            'timestamp' => 'time',
            'hidden'    => 'hidden',
            'foreign'   => 'foreign',
            'password'   => 'password',
            // '' => '',
        ];

        $columns = static::$columns->reject(function($column) {
            return in_array($column['name'], static::$except);
        });

        // dd($columns);

        $foreignValues = [];

        foreach($columns as $column) {
            
            // dump($column);

            $name = $column['name'];
            $type = $column['type'];
            
            $params = '';

            unset($fkey);
            $fkey = static::getForeignKeyValues($name);

            if(in_array($name, $hidden)) {
                $type = "hidden";
            }
            elseif(!empty($fkey)) {
                // $pgTable[$name] = 'foreign';
                $type = 'foreign';
                $foreignValues[$name] = $fkey;
            }
            else {
                    switch ($type) {
                        case 'string':
                                        if($name === 'password') $type = "password";
                                        elseif($column['length'] > 50) $type = "longstring";
                            break;
                        
                        case 'integer':
                            break;
                        
                        default:
                            break;

                    }
                    
            }

            $pgTable[$name] = $laravelToPostGreSQL[$type];

        }

        // dd($foreignValues);

        $arr['columns'] = $pgTable;
        $arr['foreign_values'] = $foreignValues;
        return $arr;
    }


    /**
     * 
     *
     * @
     */
    public static function toPostGreSQL() {
        $laravelToPostGreSQL = [
            'string'    => 'varchar',
            'decimal'   => 'numeric',
            'text'      => 'text',
            'date'      => 'date',
            'integer'   => 'integer',
            'boolean'   => 'boolean',
            'timestamp' => 'timestamp(0) without time zone',
            // '' => '',
        ];

        foreach(static::$columns as $column) {
            
            // dd($column);

            $name = $column['name'];
            $type = $column['type'];

            $params = '';

            switch ($type) {
                case 'string':
                                $params = '(' . $column['length'] .')';
                                $pgTable[$name] = $laravelToPostGreSQL[$type] . $params;
                    break;
                
                case 'decimal':
                                $params = '(' . $column['total'] . ', ' . $column['places'] .')';
                                $pgTable[$name] = $laravelToPostGreSQL[$type] . $params;
                    break;
                
                default:
                                $pgTable[$name] = $laravelToPostGreSQL[$type];
                    break;

            }
        }

        return $pgTable;
    }


    public static function runMigration($tableName, $table = null) {

        if(is_null($table)) $table = static::$table;

        printIt("\nSchema::create('$tableName', function (Blueprint \$table) {\n");

        $columns = static::getSchema($tableName);

// printIt("table: $tableName :\n"); print_r($columns);

        $tablesToIndex = [];

        foreach($columns as $column) {
            unset($type);
            unset($options);
            
// printIt("\ncolumn:\n"); print_r($column);

            $name = $column[0];


            $size = count($column);

            $type = ($size > 1 ) ? $column[1] : '';

            $options = '';

            $tableColumn = [];

            if( $type == 'increments' ) {
                printIt("\t\$table->$type('$name');\n");
                $table->increments($name);
                continue;
            }                    
            elseif(     $name == 'rememberToken' ) {
                printIt("\t\$table->rememberToken();\n");
                $table->rememberToken();
                continue;
            }                    
            elseif( $name == 'timestamps'    ) {
                printIt("\t\$table->timestamps();\n");
                $table->timestamps();
                continue;
            }
            elseif( $type == 'string') {
                printIt("\t\$table->string('$name')$options");
                $tableColumn = $table->string($name);
            }
            elseif(strstr($type, 'string:') != '') {
                $typeArr = explode(":", $type);

                $type = 'string';
                $length = $typeArr[1];
                
                printIt("\t\$table->$type('$name', $length)$options");
                $tableColumn = $table->string($name, $length);
            }
            elseif(strstr($type, 'decimal:') != '') {
                $typeArr = explode(":", $type);

                $type = 'decimal';
                $length = $typeArr[1];
                $decimal = $typeArr[2];

                printIt("\t\$table->$type('$name', $length, $decimal)$options");
                $tableColumn = $table->decimal($name, $length, $decimal);
            }
            elseif( $type == 'foreign') {
                $optionsArr = explode("|", $column[2]);
                $foreignColumnName = $optionsArr[0];
                $foreignTableName = $optionsArr[1];

                printIt("\t\$table->$type('$name')->references('$foreignColumnName')->on('$foreignTableName');\n");
                $tableColumn = $table->foreign($name)->references($foreignColumnName)->on($foreignTableName);
                continue;
            }
            elseif( $type == 'index') {
                $tablesToIndex[] = $name;
                continue;
            }
            else {
                printIt("\t\$table->$type('$name')");
                $tableColumn = $table->$type($name);
            }
            $options = [];


            if($size > 2) {
                $options = explode("|", $column[2]);
            }   

            $nullable = true;

            foreach ($options as $option) {
                if($option == 'required') {
                    $nullable = false;
                    continue;
                }

                if(strstr($option, 'default')!='') $option = str_replace(':', '(', $option) . ')';

                printIt("->$option()");
                $tableColumn->$option();
            }

            if($nullable) {
                printIt("->nullable()");
                $tableColumn->nullable();
            }

            printIt(";\n");
        }
        
        if(count($tablesToIndex)) printIt("\n\t// Index columns:\n");

        foreach($tablesToIndex as $tableName) {
            if(!empty($table)) {
                printIt("\t\$table->index('$tableName');\n");
                $table->index($tableName);
            }
        }
        
        printIt("});\n\n");

        static::$table = $table;

        foreach ($table->getColumns() as $column) {
            $arr[] = $column->toArray();
        }

        static::$columns = collect($arr); // ->keyBy('name');

        
        // // $defaultLength = ['integer'=>10, 'decimal'=>10, 'string'=>20, 'boolean'=>2, 'timestamp'=>31];

        // $types = ['integer', 'decimal', 'string', 'boolean', 'timestamp'];
        // $lengths = [10,10,20,2,15]
        
        // var_dump($json);

        // foreach (static::$columns as $column) {
        //      $column = collect($column);
        //      $name = $column->get('name');
        //      $type = $column->get('type');
        //      $length = $column->get('length', $defaultLength[$type]);
        //      echo "$type $name len=$length\n";
        //  } 

        //  // dd();
        // exit;

    }  
}