<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Box_Admin_Twinning_List_Table extends WP_List_Table {
  /** PRIVATE ATTRIBUTES **/
  protected $m_columns = array();
  protected $m_hidden = array();
  protected $m_sortable = array();
  protected $m_per_page = -1;
  protected $m_current_page = -1;
  protected $m_total_items = -1;
  protected $m_bulk_actions = array();
  
  /** PERSONAL PUBLIC FUNCTIONS **/
  public function set_columns( $columns ) {
    $this->m_columns = $columns;
  }

  public function set_hidden( $hidden ) {
    $this->m_hidden = $hidden;
  }

  public function set_sortable( $sortable_columns ) {
    $this->m_sortable = $sortable_columns;
  }

  public function set_per_page( $per_page ) {
    $this->m_per_page = $per_page;
  }

  public function set_data( $data ) {
    $this->items = $data;
  }

  public function set_bulk_actions( $bulk_actions ) {
    $this->m_bulk_actions = $bulk_actions;
  }

  /** PERSONNAL PRIVATE FUNCTIONS **/
  private function prepare_pagination() {
    if ( $this->m_per_page == -1 )
      $this->m_per_page = 1;
    $this->m_current_page = $this->get_pagenum();
    $this->m_total_items = count( $this->items );
    $this->set_pagination_args( array(
      'total_items' => $this->m_total_items,
      'per_page'    => $this->m_per_page
    ) );
  }
  
  /** OBERLOADED FUNCTIONS **/
  public function prepare_items() {
    $this->prepare_pagination();
  }

  function get_columns() {
    return $this->m_columns;
  }

  function get_hidden_columns() {
    return $this->m_hidden;
  }

  function get_sortable_columns() {
    return $this->m_sortable; 
  }
  
  function get_bulk_actions() {
    return $this->m_bulk_actions; 
  }
  
  function column_default( $item, $column_name ) {
    switch( $column_name ) {
      default:
        return $item[ $column_name ];
    }
  }
}
