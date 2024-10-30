<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://ayecode.io/
 * @since      1.0.0
 *
 * @package    Bbpress_Ms_Move
 * @subpackage Bbpress_Ms_Move/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bbpress_Ms_Move
 * @subpackage Bbpress_Ms_Move/includes
 * @author     Stiofan O'Connor <info@ayecode.io>
 */
class Bbpress_Ms_Move_Copy {


	/**
	 * Get the blog site as dropdown
	 *
	 * @since    1.0.0
	 */

	public static function blogs_dropdown( $name = 'existing_blog' ) {
		global $wpdb;
		$query = $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d  ORDER BY registered DESC LIMIT 0, 100", $wpdb->siteid);
		$blogs = $wpdb->get_results( $query, ARRAY_A );

		$dropdown = '';

		foreach ( $blogs as $blog ) {
			$id = $blog['blog_id'];
			$title = get_blog_option( $id, 'blogname' );
			$title .= ' - ' . get_home_url( $id );
			$dropdown .= "<option value='{$id}'>{$title}</option>";
		}
		$dropdown = "<select name='{$name}'>{$dropdown}</select>";
		echo $dropdown;
	}

	public function ajax_handler(){
		global $wpdb;

		// security checks
		if( !current_user_can('administrator') ){wp_die();}
		check_ajax_referer( 'bbpc-ajax-security', 'security' );

		if(isset($_POST['bbpc_action']) && $_POST['bbpc_action']){
			$bbpc_action = esc_attr($_POST['bbpc_action']);

			$bbpc_from = isset($_POST['bbpc_from']) ? absint($_POST['bbpc_from']) : '';
			$bbpc_to = isset($_POST['bbpc_to']) ? absint($_POST['bbpc_to']) : '';
			$bbpc_offset = isset($_POST['bbpc_offset']) ? absint($_POST['bbpc_offset']) : 0;
			$bbpc_total = isset($_POST['bbpc_total']) ? absint($_POST['bbpc_total']) : 0;
			$limit = 100;

			if($bbpc_action=='copy_settings'){

				$both_have_bbp = true;
				if(!get_blog_option( $bbpc_from, '_bbp_db_version' )){$both_have_bbp = false;}
				if(!get_blog_option( $bbpc_to, '_bbp_db_version' )){$both_have_bbp = false;}

				if(!$both_have_bbp){
					echo json_encode(array('error'=>'Both sites must have bbPress active'));
				}else{

					$this->copy_settings($bbpc_from,$bbpc_to);
					echo json_encode(array('success'=>'1'));
				}
			}elseif($bbpc_action=='copy_forum_structure'){

				$cpt = 'forum';

				if(!$bbpc_total){
					$bbpc_total = $this->total_posts($bbpc_from,$cpt);

				}else{
					$count = $this->move_posts($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$cpt);
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_topics'){

				$cpt = 'topic';

				if(!$bbpc_total){
					$bbpc_total = $this->total_posts($bbpc_from,$cpt );

				}else{
					$count = $this->move_posts($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$cpt );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_replies'){

				$cpt = 'reply';

				if(!$bbpc_total){
					$bbpc_total = $this->total_posts($bbpc_from,$cpt );

				}else{
					$count = $this->move_posts($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$cpt );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_terms'){

				$tax = 'topic-tag';

				if(!$bbpc_total){
					$bbpc_total = $this->total_terms($bbpc_from,$tax);

				}else{
					$count = $this->move_terms($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$tax );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_term_relationships'){

				$tax = 'topic-tag';

				if(!$bbpc_total){
					$bbpc_total = $this->total_term_relationships($bbpc_from,$tax);

				}else{
					$count = $this->move_term_relationships($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$tax );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_user_subscriptions'){

				$meta_key = '_bbp_subscriptions';

				if(!$bbpc_total){
					$bbpc_total = $this->total_user_metas($bbpc_from,$meta_key);

				}else{
					$count = $this->move_user_metas($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$meta_key );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_user_capabilities'){

				$meta_key = 'capabilities';

				if(!$bbpc_total){
					$bbpc_total = $this->total_user_metas($bbpc_from,$meta_key);

				}else{
					$count = $this->move_user_metas($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$meta_key );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_user_levels'){

				$meta_key = 'user_level';

				if(!$bbpc_total){
					$bbpc_total = $this->total_user_metas($bbpc_from,$meta_key);

				}else{
					$count = $this->move_user_metas($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$meta_key );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='copy_forum_attachments'){

				$cpt = 'attachment';

				if(!$bbpc_total){
					$bbpc_total = $this->total_post_attachments($bbpc_from,$cpt );

				}else{
					$count = $this->move_post_attachments($bbpc_from,$bbpc_to,$limit,$bbpc_offset,$cpt );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='delete_settings'){

				$this->delete_settings($bbpc_from);
				echo json_encode(array('success'=>'1'));

			}elseif($bbpc_action=='delete_forum_attachments'){

				$cpt = 'attachment';

				if(!$bbpc_total){
					$bbpc_total = $this->total_post_attachments($bbpc_from,$cpt );

				}else{
					$count = $this->delete_post_attachments($bbpc_from,$limit,0,$cpt );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='delete_forum_structure'){

				$cpt = 'forum';

				if(!$bbpc_total){
					$bbpc_total = $this->total_posts($bbpc_from,$cpt);
				}else{
					$count = $this->remove_posts($bbpc_from,$limit,0,$cpt);
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='delete_forum_topics'){

				$cpt = 'topic';

				if(!$bbpc_total){
					$bbpc_total = $this->total_posts($bbpc_from,$cpt );

				}else{
					$count = $this->remove_posts($bbpc_from,$limit,0,$cpt );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='delete_forum_replies'){

				$cpt = 'reply';

				if(!$bbpc_total){
					$bbpc_total = $this->total_posts($bbpc_from,$cpt );

				}else{
					$count = $this->remove_posts($bbpc_from,$limit,0,$cpt );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='delete_forum_term_relationships'){

				$tax = 'topic-tag';

				if(!$bbpc_total){
					$bbpc_total = $this->total_term_relationships($bbpc_from,$tax);

				}else{
					$count = $this->remove_term_relationships($bbpc_from,$limit,0,$tax );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='delete_forum_terms'){

				$tax = 'topic-tag';

				if(!$bbpc_total){
					$bbpc_total = $this->total_terms($bbpc_from,$tax);

				}else{
					$count = $this->remove_terms($bbpc_from,$limit,0,$tax );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}elseif($bbpc_action=='delete_forum_user_subscriptions'){

				$meta_key = '_bbp_subscriptions';

				if(!$bbpc_total){
					$bbpc_total = $this->total_user_metas($bbpc_from,$meta_key);

				}else{
					$count = $this->remove_user_metas($bbpc_from,$limit,0,$meta_key );
					$bbpc_offset = $bbpc_offset+$count;
				}

				echo json_encode(array(
					'success'=>'1',
					'total'=> $bbpc_total,
					'offset'=> $bbpc_offset
				));
			}
			
		}

		wp_die();
	}

	public function move_post_attachments($bbpc_from,$bbpc_to,$limit=100,$offset=0,$cpt){
		global $wpdb;

		switch_to_blog( $bbpc_from );
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT p.ID FROM $wpdb->posts p INNER JOIN $wpdb->posts pp ON p.post_parent=pp.ID WHERE p.post_type=%s AND pp.post_type IN('reply','topic') AND p.post_status IN('publish','private','hidden','pending','trash','closed','spam','inherit') LIMIT %d,%d",
				$cpt,
				$offset,
				$limit
			)
		);



		restore_current_blog();

		if(!empty($posts)){
			foreach($posts as $post){
				$this->move_post_attachment($post,$bbpc_from,$bbpc_to,$cpt);
			}
		}

		return count($posts);
	}

	public function move_post_attachment($post,$bbpc_from,$bbpc_to,$cpt){

		global $wpdb;

		switch_to_blog( $bbpc_from );
		$meta = get_post_custom( $post->ID );

		if(!isset($post->post_type)){
			$post = get_post($post->ID);
		}

		$from_upload_dir = wp_upload_dir();
		restore_current_blog();

		switch_to_blog( $bbpc_to );

		// insert wont work with id set to we fake an import
		$post->import_id = $post->ID;

		// if post already exists then bail
		if ( $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID = %d", $post->ID) ) ) {
			return;
		}
		unset($post->ID);


		wp_insert_post( $post );

		$this->migrate_meta( $post->import_id, $meta );

		// copy attachment file over
		if( isset($meta['_wp_attached_file'][0]) ){
			$to_upload_dir = wp_upload_dir();


			$a_meta = maybe_unserialize($meta['_wp_attachment_metadata'][0]);



			if(!copy(trailingslashit($from_upload_dir['basedir']).$meta['_wp_attached_file'][0] , trailingslashit($to_upload_dir['basedir']).$meta['_wp_attached_file'][0] )){
				if(mkdir(dirname(trailingslashit($to_upload_dir['basedir']).$meta['_wp_attached_file'][0]), 0777, true)){
					copy(trailingslashit($from_upload_dir['basedir']).$meta['_wp_attached_file'][0] , $to_upload_dir['basedir'].$meta['_wp_attached_file'][0] );
				}
			}

			// folder should exist now so lets copy the rest
			if(!empty($a_meta['sizes'])){
				foreach($a_meta['sizes'] as $size) {
					$file_from = trailingslashit(dirname(trailingslashit( $from_upload_dir['basedir'] ) . $meta['_wp_attached_file'][0] ) ).$size['file'];
					$file_to = trailingslashit(dirname(trailingslashit( $to_upload_dir['basedir'] ) . $meta['_wp_attached_file'][0] ) ).$size['file'];
					copy( $file_from, $file_to );

				}

			}



		}


		restore_current_blog();


	}

	public function delete_post_attachments($bbpc_from,$limit=100,$offset=0,$cpt){
		global $wpdb;

		switch_to_blog( $bbpc_from );
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT p.ID FROM $wpdb->posts p INNER JOIN $wpdb->posts pp ON p.post_parent=pp.ID WHERE p.post_type=%s AND pp.post_type IN('reply','topic') AND p.post_status IN('publish','private','hidden','pending','trash','closed','spam','inherit') LIMIT %d,%d",
				$cpt,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($posts)){
			foreach($posts as $post){
				$this->delete_post_attachment($post,$bbpc_from);
			}
		}

		return count($posts);
	}

	public function delete_post_attachment($post,$bbpc_from){

		switch_to_blog( $bbpc_from );

		wp_delete_attachment( $post->ID, true );

		restore_current_blog();

	}

	public function remove_user_metas($bbpc_from,$limit=100,$offset=0,$meta_key){
		global $wpdb;

		switch_to_blog( $bbpc_from );

		$metas = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->usermeta
				WHERE meta_key = %s
				LIMIT %d,%d",
				$wpdb->prefix . $meta_key,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($metas)){
			foreach($metas as $meta){
				$this->remove_user_meta($meta,$bbpc_from,$meta_key);
			}
		}

		return count($metas);
	}

	public function remove_user_meta($meta,$bbpc_from,$meta_key){

		global $wpdb;

		switch_to_blog( $bbpc_from );


		delete_user_meta($meta->user_id, $wpdb->prefix . $meta_key );


		restore_current_blog();
	}

	public function move_user_metas($bbpc_from,$bbpc_to,$limit=100,$offset=0,$meta_key){
		global $wpdb;

		switch_to_blog( $bbpc_from );

		$metas = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->usermeta
				WHERE meta_key = %s
				LIMIT %d,%d",
				$wpdb->prefix . $meta_key,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($metas)){
			foreach($metas as $meta){
				$this->move_user_meta($meta,$bbpc_to,$meta_key);
			}
		}

		return count($metas);
	}

	public function move_user_meta($meta,$bbpc_to,$meta_key){

		global $wpdb;

		switch_to_blog( $bbpc_to );


		update_user_meta($meta->user_id, $wpdb->prefix . $meta_key , maybe_unserialize($meta->meta_value) );


		restore_current_blog();
	}

	public function move_term_relationships($bbpc_from,$bbpc_to,$limit=100,$offset=0,$tax){
		global $wpdb;

		switch_to_blog( $bbpc_from );

		$terms = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->term_taxonomy tt 
				INNER JOIN $wpdb->term_relationships tr 
				ON tt.term_taxonomy_id = tr.term_taxonomy_id
				WHERE tt.taxonomy = %s
				LIMIT %d,%d",
				$tax,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($terms)){
			foreach($terms as $term){
				$this->move_term_relationship($term,$bbpc_to,$tax);
			}
		}

		return count($terms);
	}

	public function remove_term_relationships($bbpc_from,$limit=100,$offset=0,$tax){
		global $wpdb;

		switch_to_blog( $bbpc_from );

		$terms = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->term_taxonomy tt 
				INNER JOIN $wpdb->term_relationships tr 
				ON tt.term_taxonomy_id = tr.term_taxonomy_id
				WHERE tt.taxonomy = %s
				LIMIT %d,%d",
				$tax,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($terms)){
			foreach($terms as $term){
				$this->remove_term_relationship($term,$bbpc_from,$tax);
			}
		}

		return count($terms);
	}

	public function remove_term_relationship($term,$bbpc_from,$tax){

		global $wpdb;

		switch_to_blog( $bbpc_from );


		$wpdb->delete(
			$wpdb->term_relationships,
			array(
				'object_id' => $term->object_id,
				'term_taxonomy_id' => $term->term_taxonomy_id,
				'term_order' => $term->term_order,
			),
			array(
				'%d',
				'%d',
				'%d'
			)
		);

		restore_current_blog();
	}

	public function move_term_relationship($term,$bbpc_to,$tax){

		global $wpdb;

		switch_to_blog( $bbpc_to );


		$wpdb->insert(
			$wpdb->term_relationships,
			array(
				'object_id' => $term->object_id,
				'term_taxonomy_id' => $term->term_taxonomy_id,
				'term_order' => $term->term_order,
			),
			array(
				'%d',
				'%d',
				'%d'
			)
		);

		restore_current_blog();
	}

	public function move_terms($bbpc_from,$bbpc_to,$limit=100,$offset=0,$tax){
		global $wpdb;

		switch_to_blog( $bbpc_from );

		$terms = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->term_taxonomy tt 
				INNER JOIN $wpdb->terms t 
				ON tt.term_id = t.term_id
				WHERE tt.taxonomy = %s
				LIMIT %d,%d",
				$tax,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($terms)){
			foreach($terms as $term){
				$this->move_term($term,$bbpc_to,$tax);
			}
		}

		return count($terms);
	}

	public function remove_terms($bbpc_from,$limit=100,$offset=0,$tax){
		global $wpdb;

		switch_to_blog( $bbpc_from );

		$terms = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->term_taxonomy tt 
				INNER JOIN $wpdb->terms t 
				ON tt.term_id = t.term_id
				WHERE tt.taxonomy = %s
				LIMIT %d,%d",
				$tax,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($terms)){
			foreach($terms as $term){
				switch_to_blog( $bbpc_from );
				wp_delete_term( $term->term_id, $tax );
				restore_current_blog();
			}
		}

		return count($terms);
	}


	public function move_term($term,$bbpc_to,$tax){

		global $wpdb;

		switch_to_blog( $bbpc_to );

		// if term already exists then bail
		if ( $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms WHERE term_id = %d", $term->term_id) ) ) {
			return;
		}

		$wpdb->insert(
			$wpdb->terms,
			array(
				'term_id' => $term->term_id,
				'name' => $term->name,
				'slug' => $term->slug,
				'term_group' => $term->term_group
			),
			array(
				'%d',
				'%s',
				'%s',
				'%d'
			)
		);

		$wpdb->insert(
			$wpdb->term_taxonomy,
			array(
				'term_taxonomy_id' => $term->term_taxonomy_id,
				'term_id' => $term->term_id,
				'taxonomy' => $term->taxonomy,
				'description' => $term->description,
				'parent' => $term->parent,
				'count' => $term->count
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%d',
				'%d'
			)
		);

		restore_current_blog();
	}

	public function move_posts($bbpc_from,$bbpc_to,$limit=100,$offset=0,$cpt){
		global $wpdb;

		switch_to_blog( $bbpc_from );
		$posts = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM $wpdb->posts WHERE post_type=%s AND post_status IN('publish','private','hidden','pending','trash','closed','spam') LIMIT %d,%d",
					$cpt,
					$offset,
					$limit
				)
			);

		restore_current_blog();

		if(!empty($posts)){
			foreach($posts as $post){
				$this->move_post($post,$bbpc_from,$bbpc_to,$cpt);
			}
		}

		return count($posts);
	}

	public function remove_posts($bbpc_from,$limit=100,$offset=0,$cpt){
		global $wpdb;

		switch_to_blog( $bbpc_from );
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->posts WHERE post_type=%s AND post_status IN('publish','private','hidden','pending','trash','closed','spam') LIMIT %d,%d",
				$cpt,
				$offset,
				$limit
			)
		);

		restore_current_blog();

		if(!empty($posts)){
			foreach($posts as $post){
				$this->remove_post($post,$bbpc_from,$cpt);
			}
		}

		return count($posts);
	}

	public function move_post($post,$bbpc_from,$bbpc_to,$cpt){

		global $wpdb;

		switch_to_blog( $bbpc_from );
		$meta = get_post_custom( $post->ID );
		if(!isset($post->post_type)){
			$post = get_post($post->ID);
		}
		restore_current_blog();

		switch_to_blog( $bbpc_to );

		// insert wont work with id set to we fake an import
		$post->import_id = $post->ID;

		// if post already exists then bail
		if ( $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID = %d", $post->ID) ) ) {
			return;
		}
		unset($post->ID);


		wp_insert_post( $post );

		$this->migrate_meta( $post->import_id, $meta );

		restore_current_blog();


	}

	public function remove_post($post,$bbpc_from,$cpt){

		global $wpdb;

		switch_to_blog( $bbpc_from );

		wp_delete_post($post->ID, true);

		restore_current_blog();

	}

	public function comma_tags($tags) {
		$tag_array = array();
		if ( empty( $tags ) ) {
			return $tags;
		}

		foreach ( (array) $tags as $tag ) {
			$tag_array[] = $tag->name;
		}
		return join( ',', $tag_array );
	}

	public function migrate_meta($post_id,$meta ){
		if ( empty( $meta ) )
			return;

		foreach ( $meta as $meta_key => $meta_values ) {
			foreach ( $meta_values as $meta_value ) {
				add_post_meta( $post_id, $meta_key, maybe_unserialize($meta_value) );
			}
		}
	}

	public function total_posts($bbpc_from,$cpt){
		global $wpdb;
		switch_to_blog( $bbpc_from );
		$total = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->posts WHERE post_type=%s AND post_status IN('publish','private','hidden','pending','trash','closed','spam')",
				$cpt
			)
		);
		restore_current_blog();

		return $total;
	}

	public function total_post_attachments($bbpc_from,$cpt){
		global $wpdb;
		switch_to_blog( $bbpc_from );
		$total = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->posts p INNER JOIN $wpdb->posts pp ON p.post_parent=pp.ID WHERE p.post_type=%s AND pp.post_type IN('reply','topic') AND p.post_status IN('publish','private','hidden','pending','trash','closed','spam','inherit')",
				$cpt
			)
		);
		restore_current_blog();

		return $total;
	}

	public function total_terms($bbpc_from,$tax){
		global $wpdb;
		switch_to_blog( $bbpc_from );
		$total = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->term_taxonomy tt 
				INNER JOIN $wpdb->terms t 
				ON tt.term_id = t.term_id
				WHERE tt.taxonomy = %s",
				$tax
			)
		);
		restore_current_blog();

		return $total;
	}

	public function total_term_relationships($bbpc_from,$tax){
		global $wpdb;
		switch_to_blog( $bbpc_from );
		$total = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->term_taxonomy tt 
				INNER JOIN $wpdb->term_relationships tr 
				ON tt.term_taxonomy_id = tr.term_taxonomy_id
				WHERE tt.taxonomy = %s",
				$tax
			)
		);
		restore_current_blog();

		return $total;
	}

	public function total_user_metas($bbpc_from,$meta_key){
		global $wpdb;
		switch_to_blog( $bbpc_from );
		$total = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->usermeta 
				WHERE meta_key = %s",
				$wpdb->prefix . $meta_key
			)
		);
		restore_current_blog();

		return $total;
	}

	public function setting_array(){
		return array(
			'_bbp_edit_lock',
			'_bbp_throttle_time',
			'_bbp_allow_anonymous',
			'_bbp_allow_global_access' ,
			'_bbp_default_role' ,
			'_bbp_allow_revisions' ,
			'_bbp_enable_favorites' ,
			'_bbp_enable_subscriptions' ,
			'_bbp_allow_topic_tags' ,
			'_bbp_allow_search' ,
			'_bbp_use_wp_editor' ,
			'_bbp_use_autoembed' ,
			'_bbp_thread_replies_depth' ,
			'_bbp_allow_threaded_replies' ,
			'_bbp_topics_per_page' ,
			'_bbp_replies_per_page' ,
			'_bbp_topics_per_rss_page' ,
			'_bbp_replies_per_rss_page' ,
			'_bbp_root_slug' ,
			'_bbp_include_root' ,
			'_bbp_show_on_root' ,
			'_bbp_forum_slug' ,
			'_bbp_topic_slug' ,
			'_bbp_topic_tag_slug' ,
			'_bbp_view_slug' ,
			'_bbp_reply_slug' ,
			'_bbp_search_slug' ,
			'_bbp_user_slug' ,
			'_bbp_topic_archive_slug' ,
			'_bbp_reply_archive_slug' ,
			'_bbp_user_favs_slug' ,
			'_bbp_user_subs_slug' ,
			'_bbp_private_forums' ,
			'_bbp_hidden_forums' ,
			'_bbp_super_sticky_topics' ,
			'_bbps_default_status' ,
			'_bbps_enable_post_count' ,
			'_bbps_enable_user_rank' ,
			'_bbps_status_permissions' ,
			'_bbps_reply_count' ,
			'_bbps_used_status' ,
			'_bbps_enable_topic_move' ,
			'_bbps_status_permissions_urgent' ,
			'widget_bbps_support_hours_widget' ,
			'widget_bbps_support_resolved_count_widget' ,
			'widget_bbps_support_urgent_topics_widget' ,
			'widget_bbps_support_recently_resolved_widget' ,
			'widget_bbps_claimed_topics_widget' ,
			'widget_bbps_support_register_widget' ,
			'ja_bbp_notification_email_template' ,
			'gd-bbpress-attachments' ,
			'_bbp_super_sticky_topics' ,
			'ja_bbp_notification_email_addresses' ,
			'widget_bbpsw-search' ,
			'widget_bbp_views_widget' ,
			'_bbps_notification_subject' ,
			'_bbps_notification_message' ,
			'widget_bbp_forums_widget' ,
			'widget_bbp_search_widget' ,
			'widget_bbp_replies_widget' ,
			'_bbps_claim_topic_display' ,
			'_bbps_claim_topic' ,
			'_bbp_enable_akismet' ,
			'_bbps_topic_assign' ,
			'widget_bbp_login_widget' ,
			'widget_bbp_stats_widget' ,
			'widget_bbp_topics_widget' ,
		);
	}

	public function copy_settings($bbpc_from,$bbpc_to){
		
		$settings = $this->setting_array();

		foreach($settings as $setting){
			if($value = get_blog_option( $bbpc_from, $setting )){update_blog_option( $bbpc_to, $setting , $value ); }
		}

		return true;
	}

	public function delete_settings($bbpc_from){

		$settings = $this->setting_array();

		foreach($settings as $setting){
			delete_blog_option( $bbpc_from, $setting );
		}

		return true;
	}
	
	
	public function copy_actions(){
		return apply_filters( 'bbpc_copy_actions', array(
			"bbpc_settings" => 1,
			"bbpc_forum_structure" => 1,
			"bbpc_forum_topics" => 1,
			"bbpc_forum_replies" => 1,
			"bbpc_forum_terms" => 1,
			"bbpc_forum_term_relationships" => 1,
			"bbpc_forum_user_subscriptions" => 1,
			"bbpc_forum_user_capabilities" => 1,
			"bbpc_forum_user_levels" => 1,
			"bbpc_forum_attachments" => 1,
		));
	}

	public function delete_actions(){
		return apply_filters( 'bbpc_delete_actions', array(
			"bbpc_settings" => 0,
			"bbpc_forum_attachments" => 0,
			"bbpc_forum_replies" => 1,
			"bbpc_forum_topics" => 1,
			"bbpc_forum_structure" => 1,
			"bbpc_forum_term_relationships" => 1,
			"bbpc_forum_terms" => 1,
			"bbpc_forum_user_subscriptions" => 1,
		));
	}

	public function output_settings($settings){
		$output = '';
		foreach($settings as $setting => $set){
			$checked = $set ? "checked='checked'" : '';
			$output .= "<li data-function='".$setting."'>";
			$output .= "<h2>".ucwords(str_replace(array('bbpc','_'),array('',' '),$setting)).":</h2>";
			$output .= "<span id='".$setting."_progress'></span>";
			$output .= '<input type="checkbox" id="'.$setting.'_set" value="1" '.$checked.' class="bbpc-checkbox" >';
			$output .= '<progress id="'.$setting.'" max="100" value="0"></progress>';
			$output .= "</li>";
		}

		return $output;
	}

}
