<?php
/**
 * Model class <i>SIB_Model_Country</i> represents country code
 * @package SIB_Model
 */

class SIB_Model_Country
{
    /**
     * Tab table name
     */
    const table_name = 'sib_model_country';

    /** Create Table */
    public static function create_table()
    {
        global $wpdb;
        // create list table
        $creation_query =
            'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix  .self::table_name . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`iso_code` varchar(255),
            `call_prefix` int(10),
            PRIMARY KEY (`id`)
			);';
        $result = $wpdb->query( $creation_query );

        return $result;
    }

    /**
     * Remove table
     */
    public static function remove_table()
    {
        global $wpdb;
        $query = 'DROP TABLE IF EXISTS ' . $wpdb->prefix  .self::table_name . ';';
        $wpdb->query($query);
    }

    /**
     * Get data by id
     * @param $id
     */
    public static function get_prefix($code)
    {
        global $wpdb;
        $query = $wpdb->prepare('select call_prefix from ' . $wpdb->prefix  .self::table_name . ' ' . ' where iso_code= %s ',array(esc_sql($code)));
        $results = $wpdb->get_var($query);

        if($results != null)
            return $results;
        else
            return false;
    }

    /** Add record */
    static function add_record($iso_code, $call_prefix)
    {
        global $wpdb;

        $query = $wpdb->prepare("INSERT INTO " .  $wpdb->prefix  .self::table_name  . ' ' . "(iso_code,call_prefix)  VALUES (%s,%d)",array(esc_sql($iso_code),esc_sql($call_prefix)));

        $wpdb->query( $query );

        return true;

    }

    public static function Initialize($data){
        foreach($data as $code=>$prefix){
            self::add_record($code, $prefix);
        }
    }

    /** Add prefix to the table */
    public static function add_prefix() {
        global $wpdb;
        if ( $wpdb->get_var( "SHOW TABLES LIKE '" . self::table_name . "'" ) == self::table_name ) {
                $query = 'ALTER TABLE ' . self::table_name . ' RENAME TO ' . $wpdb->prefix . self::table_name . ';';
                $wpdb->query( $query ); // db call ok; no-cache ok.
            }
    }

}
