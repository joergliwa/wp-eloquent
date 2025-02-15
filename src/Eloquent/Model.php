<?php
namespace WeDevs\ORM\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Model Class
 *
 * @package WeDevs\ERP\Framework
 */
abstract class Model extends Eloquent {

    protected $db_prefix;

    /**
     * @param array $attributes
     */
    public function __construct( array $attributes = array() ) {
        static::$resolver = new Resolver();

        parent::__construct( $attributes );

        $this->db_prefix = $this->getConnection()->db->prefix;
    }

    /**
     * Get the database connection for the model.
     *
     * @return Database
     */
    public function getConnection() {
        return Database::instance();
    }

    /**
     * Get the table associated with the model.
     *
     * Append the WordPress table prefix with the table name if
     * no table name is provided
     *
     * @return string
     */
    public function getTable() {
        if(isset($this->table)) {
            return substr($this->table, 0, strlen($this->db_prefix)) === $this->db_prefix ? $this->table : $this->db_prefix . $this->table;
        }

        $this->table =  $this->db_prefix.parent::getTable();
        return $this->table;
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder() {

        $connection = $this->getConnection();

        return new Builder(
            $connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
        );
    }
}
