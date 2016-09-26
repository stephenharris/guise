<?php

namespace StephenHarris\Guise\Columns;

class Column_View_Store {

    protected $columns = array();

    public function store( $column_view, $slug, $index = -1 ) {

        if ( ! isset( $this->columns[$slug] ) ) {
            $this->columns[$slug] = array();
        }

        $this->columns[$slug][$this->get_key($column_view)] = array(
            'column' => $column_view,
            'index'  => $index
        );

    }

    protected function get_key( $column_view ) {
        return spl_object_hash( $column_view );
    }

    public function insert_columns_for_object( $slug, $columns ) {

        if ( ! $this->has_columns_for_object( $slug ) ) {
            return $columns;
        }

        foreach( $this->columns[$slug] as $column_key => $column_properties ) {
            $columns = $this->insert_at_index( array( $column_key => $column_properties['column']->label() ), $column_properties['index'], $columns );
        }
        return $columns;
    }

    public function insert_sortable_columns_for_object( $slug, $sortable_columns ) {

        if ( ! $this->has_columns_for_object( $slug ) ) {
            return $sortable_columns;
        }

        foreach( $this->columns[$slug] as $column_key => $column_properties ) {
            if ( $column_properties['column'] instanceof Sortable_Column_View ) {
                $sortable_columns[$column_key] = $column_properties['column']->sort_by();
            }
        }
        return $sortable_columns;
    }

    public function get_column_view( $slug, $column_key ){

        if ( ! $this->has_column_for_object( $slug, $column_key ) ) {
            return new Null_Column_View();
        }

        return $this->columns[$slug][$column_key]['column'];

    }

    protected function has_column_for_object( $slug, $column_name ) {
        if ( ! $this->has_columns_for_object( $slug ) ) {
            return false;
        }
        return isset( $this->columns[$slug][$column_name] );
    }

    protected function has_columns_for_object( $slug ) {
        return isset( $this->columns[$slug] ) && ! empty( $this->columns[$slug]  );
    }

    protected function insert_at_index( $key_value, $index, $array ) {

        if ( $index < 0 || $index > count( $array ) ) {
            $result = $array + $key_value;
        } else {
            $result = array_slice($array, 0, $index, true) + $key_value + array_slice($array, $index, count($array)-$index, true);
        }

        return $result;
    }

}
