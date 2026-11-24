<?php


    // DATA TABLE FUNCTION CLASS
    function simple ( $request, $table, $primaryKey, $columns , $where=null, $select='*')
    {

        // Build the SQL query string from the request
        $limit = $this->limit( $request, $columns );
        $offset = $this->offset( $request, $columns );
        $order = $this->order( $request, $columns );
        $search = $this->filter( $request, $columns );

        // Main query to actually get the data
        $data = $this->general_model->datagrabs(array(
            'tabel'=>$table,
            'where'=>$where,
            'select'=>$select,
            'search'=>$search,
            'limit'=>$limit,
            'offset'=>($offset == '' ? 0 : $offset),
            'order'=>$order,
            ));

        // cek($offset);
        // cek($this->db->last_query());

        // Data set length after filtering
        $resFilterLength = $this->general_model->datagrabs(array(
            'tabel'=>$table,
            'where'=>$where,
            'count'=>'count('.$primaryKey.') jml',
            'search'=>$search,
            ))->row()->jml;
        $recordsFiltered = $resFilterLength;

        // Total data set length
        $resTotalLength = $this->general_model->datagrabs(array(
            'tabel'=>$table,
            'where'=>$where,
            'count'=>'count('.$primaryKey.') jml'
            ))->row()->jml;
        $recordsTotal = $resTotalLength;

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data->result_array() )
        );
    }

function data_output ( $columns, $data )
    {

        // cek($columns);
        // cek($data);
        $out = array();

        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();

            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];

                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                }
                else {
                    $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
                }
            }

            $out[] = $row;
        }

        return $out;
    }


function limit ( $request, $columns )
    {
        $limit = '';
            if (isset($request['length'])) {
                if ( $request['length'] != -1 ) {
                $limit = intval($request['length']);
            }
        }
        

        return $limit;
    }

function offset ( $request, $columns )
    {
        $offset = '';

        if ( $request['start'] != -1 ) {
            $offset = intval($request['start']);
        }

        return $offset;
    }

function order ( $request, $columns )
    {
        $order = '';

        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            $dtColumns = $this->pluck( $columns, 'dt' );

            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['orderable'] == 'true' ) {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = '`'.$column['db'].'` '.$dir;
                }
            }

            $order = implode(', ', $orderBy);
        }

        return $order;
    }


 function filter ( $request, $columns )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = $this->pluck( $columns, 'dt' );

        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];

            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['searchable'] == 'true' ) {
                 
                    $globalSearch[$column['db']] = $str;
                }
            }
        }

        // Individual column filtering
        if ( isset( $request['columns'] ) ) {
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                $str = $requestColumn['search']['value'];

                if ( $requestColumn['searchable'] == 'true' &&
                 $str != '' ) {
                
                    $columnSearch[$column['db']] = $str;
                }
            }
        }

        // Combine the filters into a single string
        $where = array();

        if ( count( $globalSearch ) ) {
            $where = $globalSearch;
        }

        if ( count( $columnSearch ) ) {
            $where = count($where) > 0 ?
                array_merge_recursive($where, $columnSearch) :
                $columnSearch;
        }

        return $where;
    }

    function pluck ( $a, $prop )
    {
        $out = array();

        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            $out[] = $a[$i][$prop];
        }

        return $out;
    }

    function bind ( &$a, $val, $type )
    {
        $key = ':binding_'.count( $a );

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );

        return $key;
    }
