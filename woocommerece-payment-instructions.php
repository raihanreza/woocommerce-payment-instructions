<?php
/**
 * Plugin Name:       WooCommerce Payment Instructions
 * Description:       Provide Unique Payment Instructions for each Customers.
 * Version:           1.0.0
 * Author:            Raihan Reza
 * Author URI:        http://www.pkweb.in/
 *
 * Copyright:         © 2015 PKweb.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC PaymemntInstrcution class
 */
class WC_PIC {
	
public function __construct() {
// Checking Woocommarce Activated
	define( 'WC_PIC_VERSION', '2.0.0' );
	define( 'WC_PIC_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
	// update menu
	add_action( 'admin_menu', array( $this, 'add_woo_menu_item' ),'60' );
	add_action( 'admin_enqueue_scripts', array( $this, 'pic_form_styles' ) );
	add_action( 'plugins_loaded', array( $this, 'init' ) );
	add_filter('wp_mail_content_type',array( $this, 'set_mail_content_type' ) );
	// Install
	register_activation_hook( __FILE__, array( $this, 'table_install' ) );
}

public function init() {
//hook to add the meta box data
add_action("add_meta_boxes", array( $this, 'set_paydetails_meta_box' ) );
//hook to save the meta box data
add_action( 'save_post', array( $this, 'pay_deatils_save_meta') );
//hook to display the payment deatils in front end
add_action( 'woocommerce_order_details_after_order_table',array( $this,'payment_details_display_frontend') );

}
public function set_paydetails_meta_box()
{
add_meta_box("paydetails-meta", "Payment Details", array( $this,"payment_details_box_markup"), "shop_order", "normal", "default");
}

public function set_mail_content_type(){
    return "text/html";
}
public function payment_details_box_markup( $post ) {
	
	$paymentMethod = get_post_meta( $post->ID, '_payment_method', true );
    if($paymentMethod=='cod' || $paymentMethod=='cheque' ){
    $mtcn_reff = get_post_meta( $post->ID, '_mtcn_reff', true );
	$full_name = get_post_meta( $post->ID, '_full_name', true );
	$sender_city = get_post_meta( $post->ID, '_sender_city', true );
	$sender_state = get_post_meta( $post->ID, '_sender_state', true );
	$test_question = get_post_meta( $post->ID, '_test_question', true );
	$receiver_name = get_post_meta( $post->ID, '_receiver_name', true );
	$receiver_city = get_post_meta( $post->ID, '_receiver_city', true );
	$receiver_state = get_post_meta( $post->ID, '_receiver_state', true );
	$test_answer = get_post_meta( $post->ID, '_test_answer', true );
    ?>
        <div id="order_data" class="panel order_pay_cls">
                <div class="order_data_column_container">
                <div class="order_data_column fst">
                <table class="form-table">
                <tr valign="top">
                <th scope="row">
                <label for="mtcn_reff">MTCN or Reference #</label>
                </th>
                <td class="forminp">
                <fieldset>
                  <input class="input-text regular-input " type="text" name="mtcn_reff" id="mtcn_reff" value="<?php if(!empty($mtcn_reff)) { echo $mtcn_reff; }?>" placeholder="">
                </fieldset>
                </td>
                </tr>
                </table>
                </div>
                </div>
				<div class="order_data_column_container">
                <div class="order_data_column fst">
						<h2>Sender Details</h2>
                        <table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="full_name">Full Name</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="full_name" id="full_name" value="<?php if(!empty($full_name)) { echo $full_name; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="sender_city">Sender City</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="sender_city" id="sender_city" value="<?php if(!empty($sender_city)) { echo $sender_city; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="sender_state">Sender State</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="sender_state" id="sender_state" value="<?php if(!empty($sender_state)) { echo $sender_state; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="test_question">Test Question </label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="test_question" id="test_question" value="<?php if(!empty($test_question)) { echo $test_question; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			
			</table>
                 </div>
                 <div class="order_data_column">
						<h2>Receiver Details</h2>
                        <table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="receiver_name">Receiver Name</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="receiver_name" id="receiver_name" value="<?php if(!empty($receiver_name)) { echo $receiver_name; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="receiver_city">Receiver City</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="receiver_city" id="receiver_city" value="<?php if(!empty($receiver_city)) { echo $receiver_city; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="receiver_state">Receiver State</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="receiver_state" id="receiver_state" value="<?php if(!empty($receiver_state)) { echo $receiver_state; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="test_answer">Test Answer</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="test_answer" id="test_answer" value="<?php if(!empty($test_answer)) { echo $test_answer; }?>" placeholder="">
					</fieldset>
				</td>
			</tr>
			
			</table>
                 </div>
                 <div class="clear"></div>
        </div>
        </div>
    <?php }else{
	 echo '<p>No payment methode instructions available for this order.</p>';
	 }
    }

public function pay_deatils_save_meta( $post_ID ) {
    global $post;
    if( $post->post_type == "shop_order" ) {
    if ( isset( $_POST ) ) {
        update_post_meta( $post_ID, '_mtcn_reff', strip_tags( $_POST['mtcn_reff'] ) );
		update_post_meta( $post_ID, '_full_name', strip_tags( $_POST['full_name'] ) );
		update_post_meta( $post_ID, '_sender_city', strip_tags( $_POST['sender_city'] ) );
		update_post_meta( $post_ID, '_sender_state', strip_tags( $_POST['sender_state'] ) );
		update_post_meta( $post_ID, '_test_question', strip_tags( $_POST['test_question'] ) );
		update_post_meta( $post_ID, '_receiver_name', strip_tags( $_POST['receiver_name'] ) );
		update_post_meta( $post_ID, '_receiver_city', strip_tags( $_POST['receiver_city'] ) );
		update_post_meta( $post_ID, '_receiver_state', strip_tags( $_POST['receiver_state'] ) );
		update_post_meta( $post_ID, '_test_answer', strip_tags( $_POST['test_answer'] ) );
    }
}

}

public function payment_details_display_frontend($order)
{
	$orderID = $order->id;
	$paymentMethod = get_post_meta( $orderID, '_payment_method', true );
	$get_sp_amount = get_option("sp_amount");
    if($paymentMethod=='cod' || $paymentMethod=='cheque' ){
	$orderAmount = get_post_meta( $orderID, '_order_total', true );
	$receiverID = get_post_meta( $orderID, '_payment_receiver_id', true );
	$amount_sent = get_post_meta( $orderID, '_order_total', true );
	if(empty($receiverID)) {
	global $wpdb;
	
		if($orderAmount>=$get_sp_amount && $get_sp_amount>0){
			$random_reciver_obj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wc_payment_instructions WHERE active_number!=temp_number AND payment_method='".$paymentMethod."' AND special_user_amount='1' ORDER BY RAND()");
			$reciver_number = count($random_reciver_obj); 
            }else{
			$random_reciver_obj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wc_payment_instructions WHERE active_number!=temp_number AND payment_method='".$paymentMethod."' ORDER BY RAND()"); } 
			
        if($reciver_number<1){
			$random_reciver_obj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wc_payment_instructions WHERE active_number!=temp_number AND payment_method='".$paymentMethod."' ORDER BY RAND()"); }

	$get_sender_name = get_post_meta( $orderID, '_billing_first_name', true ).' '.get_post_meta( $orderID, '_billing_last_name', true );
	$get_sender_city = get_post_meta( $orderID, '_billing_city', true );
	$get_sender_state = get_post_meta( $orderID, '_billing_state', true );
	$get_test_question = $random_reciver_obj->test_question;
	$get_mtcn_reff = '';
	if($random_reciver_obj->active_number > $random_reciver_obj->temp_number) { $temp_number = $random_reciver_obj->temp_number+1; }
	
	update_post_meta( $orderID, '_payment_receiver_id', $random_reciver_obj->id );
	update_post_meta( $orderID, '_receiver_name', $random_reciver_obj->receiver_name );
	update_post_meta( $orderID, '_receiver_city', $random_reciver_obj->receiver_city );
	update_post_meta( $orderID, '_receiver_state', $random_reciver_obj->receiver_state );
	update_post_meta( $orderID, '_test_answer', $random_reciver_obj->test_answer );
	update_post_meta( $orderID, '_full_name', $get_sender_name );
	update_post_meta( $orderID, '_sender_city', $get_sender_city );
	update_post_meta( $orderID, '_sender_state', $get_sender_state );
	update_post_meta( $orderID, '_test_question', $get_test_question );
	update_post_meta( $orderID, '_mtcn_reff', $get_mtcn_reff );
	$update = $wpdb->update( 
		"{$wpdb->prefix}wc_payment_instructions", 
		array( "temp_number" =>  $temp_number),
			array( 'id' => $random_reciver_obj->id )
		);	
	}
	
	$mtcn_reff = get_post_meta( $orderID, '_mtcn_reff', true );
	$full_name = get_post_meta( $orderID, '_full_name', true );
	$sender_city = get_post_meta( $orderID, '_sender_city', true );
	$sender_state = get_post_meta( $orderID, '_sender_state', true );
	$test_question = get_post_meta( $orderID, '_test_question', true );
	$receiver_name = get_post_meta( $orderID, '_receiver_name', true );
	$receiver_city = get_post_meta( $orderID, '_receiver_city', true );
	$receiver_state = get_post_meta( $orderID, '_receiver_state', true );
	$test_answer = get_post_meta( $orderID, '_test_answer', true );

	if(isset($_POST['wp_pi_save']) && $_POST['wp_pi_save']!='' && isset($_POST['Update'])) {
		if(!empty($_POST['full_name']) && !empty($_POST['sender_city']) && !empty($_POST['sender_state']) && !empty($_POST['test_question']))
		{
	update_post_meta( $_POST['wp_pi_save'], '_full_name', $_POST['full_name'] );
	update_post_meta( $_POST['wp_pi_save'], '_sender_city', $_POST['sender_city'] );
	update_post_meta( $_POST['wp_pi_save'], '_sender_state', $_POST['sender_state'] );
	update_post_meta( $_POST['wp_pi_save'], '_test_question', $_POST['test_question'] );
	update_post_meta( $_POST['wp_pi_save'], '_mtcn_reff', $_POST['mtcn_reff'] );
	    $msg = 'Payment Details Updated Successfully!';
		$admin_mail_to = get_bloginfo('admin_email');
		$subject = 'Payment Details';
		$message = "<table border='1'>
			  <tr>
				<th colspan='2'>Sender Details</th>
				<th colspan='2'>Receiver Details</th>
			  </tr>
			   <tr>
				<td colspan='2'>Order ID # </td>
				<td colspan='2'>".$_POST['wp_pi_save']."</td>
			  </tr>
			  <tr>
				<td colspan='2'>Order Amount# </td>
				<td colspan='2'>".$amount_sent."</td>
			  </tr>
			  <tr>
				<td colspan='2'>MTCN or Reference # </td>
				<td colspan='2'>".$_POST['mtcn_reff']."</td>
			  </tr>
			  <tr>
				<td>Full Name :</td>
				<td>".$_POST['full_name']."</td>
				<td>&nbsp;Receiver Name:</td>
				<td>".$receiver_name."</td>
			  </tr>
			  <tr>
				<td>Sender City :</td>
				<td>".$_POST['sender_city']."</td>
				<td>&nbsp;Receiver City :</td>
				<td>".$receiver_city."</td>
			  </tr>
			  <tr>
				<td>Sender State :</td>
				<td>".$_POST['sender_state']."</td>
				<td>&nbsp;Receiver State :</td>
				<td>".$receiver_state."</td>
			  </tr>
			  <tr>
				<td>Test Question :</td>
				<td>".$_POST['test_question']."</td>
				<td>&nbsp;Test Answer :</td>
				<td>".$test_answer."</td>
			  </tr>
			</table>";
		wp_mail($admin_mail_to, $subject, $message, $headers);		
		}
		else
		{
			$msg ='Fields cannot be empty!';
		}
	
	}
	
	?>
    <style type="text/css">.pay_order_column { width:49%; } .order_pay_column_container { clear:both; } .pay_order_column.fst { float:left; } .pay_order_column.snd { float:right; } .pay_order_column h4 { font-size:14px; margin:0 0 5px; } .edit { float:right; padding-top:10px; font-size:14px; }</style>
    <h2 id="payment_update">Payment Details <?php if(!isset($_POST['wp_pi_hidden'])) {?><a class="edit" onclick="document.getElementById('pay_ord_frm').submit();" href="javascript:void(0);">Edit</a><?php }?></h2>
    <?php if(isset($msg)) { echo '<p style="color:#b366a4;">'.$msg.'</p>';} ?>
    <form action="#payment_update" method="post" id="pay_ord_frm">
    <?php if(isset($_POST['wp_pi_hidden'])) {?><input type="hidden" name="wp_pi_save" value="<?=$orderID?>" /><?php } else { ?><input type="hidden" name="wp_pi_hidden" value="18111989" /><?php } ?>
    <div class="order_pay_column_container">
    <div class="order_data_column fst">
                <table class="form-table">
                <tr>
                <th scope="row">Order No:</th>
                <td><span><?=$orderID?></span></td>
                </tr>
                <tr>
                <th scope="row">Amount Sent:</th>
                <td><span><?=$amount_sent?></span></td>
                </tr>
                <tr>
                <th scope="row">MTCN or Reference #:</th>
                <td><?php if(isset($_POST['wp_pi_hidden'])) {?><input type="text" name="mtcn_reff" value="<?=$mtcn_reff?>" /><?php } else { ?><span><?=$mtcn_reff?></span><?php } ?></td>
                </tr>
                </table>
     </div>
     </div>
    <div class="order_pay_column_container">
    <div class="pay_order_column fst">
    <h4>Sender Details</h4>
	<table class="form-table">
	<tfoot>
        <tr>
            <th scope="row">Sender Full Name:</th>
            <td><?php if(isset($_POST['wp_pi_hidden'])) {?><input type="text" name="full_name" value="<?=$full_name?>" /><?php } else { ?><?=$full_name?><?php } ?></td>
        </tr>
        <tr>
            <th scope="row">Sender City:</th>
            <td><?php if(isset($_POST['wp_pi_hidden'])) {?><input type="text" name="sender_city" value="<?=$sender_city?>" /><?php } else { ?><?=$sender_city?><?php } ?></td>
        </tr>
        <tr>
            <th scope="row">Sender State:</th>
            <td><?php if(isset($_POST['wp_pi_hidden'])) {?><input type="text" name="sender_state" value="<?=$sender_state?>" /><?php } else { ?><?=$sender_state?><?php } ?></td>
        </tr>
        <tr>
            <th scope="row">Test Question:</th>
            <td><?php if(isset($_POST['wp_pi_hidden'])) {?><input type="text" name="test_question" value="<?=$test_question?>" /><?php } else { ?><?=$test_question?><?php } ?></td>
        </tr>
					</tfoot>
</table>
</div>
    <div class="pay_order_column snd">
 <h4>Receiver Details</h4>
	<table class="form-table">
    <tfoot>
         <tr>
            <th scope="row">Receiver Name:</th>
            <td><span><?=$receiver_name?></span></td>
        </tr>
        <tr>
            <th scope="row">Receiver City:</th>
            <td><?=$receiver_city?></td>
        </tr>
        <tr>
            <th scope="row">Receiver State:</th>
            <td><?=$receiver_state?></td>
        </tr>
        <tr>
            <th scope="row">Test Answer:</th>
            <td><?=$test_answer?></td>
        </tr>
	</tfoot>
</table>
</div>
    <div class="clear"></div>
    </div>
   <?php if(isset($_POST['wp_pi_hidden'])) {?> <input type="submit" name="Update" value="save" /><?php } ?>
    </form>
<?php } }

public function table_install() {
	global $wpdb;
    $installed_ver = get_option( "pic_db_version" );
	$wpdb->hide_errors();
	if ( $installed_ver != WC_PIC_VERSION ) {
	$charset_collate = $wpdb->get_charset_collate();
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( "CREATE TABLE {$wpdb->prefix}wc_payment_instructions (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			receiver_name varchar(200) NOT NULL,
			receiver_city varchar(200) NOT NULL,
			receiver_state varchar(200) NOT NULL,
			test_question text NOT NULL,
			test_answer varchar(200) NOT NULL,
			active_number bigint(20) NOT NULL,
			temp_number bigint(20) NOT NULL,
			payment_method varchar(250) NOT NULL,
			special_user_amount bigint(20) NOT NULL,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			UNIQUE KEY id (id)
			) $charset_collate;" );
			update_option( 'pic_db_version', WC_PIC_VERSION );
	}
}


public function add_woo_menu_item() {
			if ( is_admin() ) {
				add_submenu_page( 'woocommerce', __( 'Payment Instruction','woocommerce'), __( 'Payment Instruction','woocommerce' ), 'manage_woocommerce', 'payment_instruction', array(  $this,'payment_instruction_settings' ) );
			}
			return false;
		}
		
public function payment_instruction_settings() { 

global $wpdb;
if(!empty($_POST['receiver_name']) && !empty($_POST['receiver_city']) && !empty($_POST['receiver_state'])&&!empty($_POST['test_answer'])&& !empty($_POST['active_number']))
{
	$receiver_name = strip_tags($_POST['receiver_name']);
	$receiver_name_filter_value = mysql_real_escape_string($receiver_name);
	
	$receiver_city = strip_tags($_POST['receiver_city']);
	$receiver_city_filter_value = mysql_real_escape_string($receiver_city);
	
	$receiver_state = strip_tags($_POST['receiver_state']);
	$receiver_state_filter_value = mysql_real_escape_string($receiver_state);
	
	$test_question = strip_tags($_POST['test_question']);
	$test_question_filter_value = mysql_real_escape_string($test_question);
	
	$test_answer = strip_tags($_POST['test_answer']);
	$test_answer_filter_value = mysql_real_escape_string($test_answer);
	
	$active_number = strip_tags($_POST['active_number']);
	$active_number_filter_value = mysql_real_escape_string($active_number);
	
	$payment_method = strip_tags($_POST['payment_gateway']);
	$payment_method_filter_value = mysql_real_escape_string($payment_method);
	
	$special_user_amount = strip_tags($_POST['special_user_amount']);
	$special_user_amount_filter_value = mysql_real_escape_string($special_user_amount);
	
    $current_date_time = date("Y-m-d H:i:s", time());
	/*Receiver Insert Query*/
	if(isset($_POST['hidden_action']) && $_POST['hidden_action']=='add'){
		$wpdb->insert( 
		"{$wpdb->prefix}wc_payment_instructions", 
		array( 
			"receiver_name" =>  $receiver_name_filter_value, 
			"receiver_city" =>  $receiver_city_filter_value,
			"receiver_state" => $receiver_state_filter_value,
			"test_question" => $test_question_filter_value, 
			"test_answer" => $test_answer_filter_value, 
			"active_number" => $active_number_filter_value,
			"payment_method" => $payment_method_filter_value,
			"special_user_amount" => $special_user_amount_filter_value,  
			"time" => $current_date_time 
			)
		);
		$inserted_id = $wpdb->insert_id;
		
		if(!empty($inserted_id))
		{
			$msg = 'New Receiver added.';
		}
		else
		{
			$msg = 'All fileds are required!';
		}
	}
   /*Receiver Update Query*/
	if(isset($_POST['hidden_action']) && $_POST['hidden_action']=='edit' && isset($_POST['hidden_id'])){
		 $update = $wpdb->update( 
		"{$wpdb->prefix}wc_payment_instructions", 
		array( 
			"receiver_name" =>  $receiver_name_filter_value, 
			"receiver_city" =>  $receiver_city_filter_value,
			"receiver_state" => $receiver_state_filter_value,
			"test_question" => $test_question_filter_value, 
			"test_answer" => $test_answer_filter_value, 
			"active_number" => $active_number_filter_value,
			"payment_method" => $payment_method_filter_value,
			"special_user_amount" => $special_user_amount_filter_value,
			"time" => $current_date_time 
			),
			array( 'id' => base64_decode($_POST['hidden_id']))
		);
		if($update){
	      $msg = 'Receiver Record Successfully Updated!';
		}
	}
	
}
/*Delete Receiver*/
if(!empty($_GET['receiver_del'])){
	 $receiver_id = base64_decode($_GET['receiver_del']);
	 if($wpdb->delete("{$wpdb->prefix}wc_payment_instructions", array('id' => $receiver_id ))){
		  $msg = 'Receiver Record Successfully Deleted!';
		 }
	}
/*Insert Specail Amount*/
 if(isset($_REQUEST['sp_amount_input'])){
  update_option('sp_amount',$_REQUEST['sp_amount_input']);
 }

/*Edit Receiver*/
if(!empty($_GET['receiver'])){
     include_once "inc/receiver-details-edit.php";
	 }else{
	?>
		<div class="wrap woocommerce pic">
		<h2>Payment Instructions</h2>
		<?php if(isset($msg)) {echo '<div id="message" class="updated notice notice-success is-dismissible below-h2"><p>'.$msg.'</p></div>'; }?>
		<h3>Receiver Details</h3>
		<table class="wp-list-table striped widefat">
		<thead><tr>
		<th class="id">ID</th>
		<th class="rname">Receiver Name</th>
		<th class="rcity">Receiver City</th>
		<th class="rstate">Receiver State</th>
        <th class="rstate">Test Question</th>
		<th class="rstate">Test Answer</th>
		<th class="rstate">Use Times</th>
        <th class="rstate">Payment Method</th>
        <th class="rstate">
        <?php $sp_amount = get_option("sp_amount");?>
        <span id="sp_amount_input"><form action="" method="post"><input type="number" name="sp_amount_input"  value="<?=$sp_amount?>" size="20"><input type="submit" value="updata"></form></span><span class="sp_amount_lebel">Special User(higher than <a href="javascript:void(0)" id="sp_amount"><u>$<?php if(!empty($sp_amount)){echo $sp_amount;}else{echo '0';}?></u></a>)</span>
        </th>
		<th class="remove">Action</th></tr></thead>
		<?php $select_reciver = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_payment_instructions");
			  if ($select_reciver) 
				 {
					foreach($select_reciver as $select_reciver_key=>$select_reciver_obj){?>
						<tr>
						<td><?php echo $select_reciver_obj->id;?></td>
						<td><?php echo $select_reciver_obj->receiver_name;?></td>
						<td><?php echo $select_reciver_obj->receiver_city;?></td>
						<td><?php echo $select_reciver_obj->receiver_state;?></td>
                        <td><?php echo $select_reciver_obj->test_question;?></td>
						<td><?php echo $select_reciver_obj->test_answer;?></td>
						<td><?php echo $select_reciver_obj->active_number;?></td>
                        <td>
						   <?php if ($select_reciver_obj->payment_method=='cod') {echo 'Money Gram'; } ?>
                           <?php if ($select_reciver_obj->payment_method=='cheque') {echo 'Western Union'; } ?>
                        </td>
                        <td><?php if($select_reciver_obj->special_user_amount==1){echo 'true';}else{echo 'false';}?></td>
						<td>
						   <a href="?page=payment_instruction&receiver=<?php echo base64_encode($select_reciver_obj->id); ?>"><img src="<?php echo WC_PIC_PLUGIN_URL?>/images/edit_icon.gif"/></a>
						   <a href="?page=payment_instruction&receiver_del=<?php echo base64_encode($select_reciver_obj->id); ?>" onclick="return del_confirm();"><img src="<?php echo WC_PIC_PLUGIN_URL?>/images/delete.png"/></a>
						</td>
						</tr>
					<?php } 
				   }else{
					 echo '<tr><td colspan="7"><strong>No Receiver Details</strong></td></tr>';
					} ?>
		</table>
		<input type="button" name="add-pic" value="Add Receiver Details" class="button-primary add-btn">
		</div>
		<div class="wrap add-pic">
		  <div id="faq-wrapper">
			<form method="post" action="?page=payment_instruction" class="add-form">
             <input type="hidden" name="hidden_action" value="add"/>
			<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="receiver_name">Receiver Name</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="receiver_name" id="receiver_name" value="" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="receiver_city">Receiver City</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="receiver_city" id="receiver_city" value="" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="receiver_state">Receiver State</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="receiver_state" id="receiver_state" value="" placeholder="">
					</fieldset>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row">
					<label for="test_question">Test Question</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="test_question" id="test_question" value="" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="test_answer">Test Answer</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="test_answer" id="test_answer" value="" placeholder="">
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="active_number">Maximum Number of Time to use a payment</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="number" name="active_number" id="active_number" value="" placeholder="">
					</fieldset>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row">
					<label for="payment_gateway">Payment Gateway</label>
				</th>
				<td class="forminp">
					<fieldset>
						<select name="payment_gateway">
                            <option value="cod">Money Gram</option>
                            <option value="cheque">Western Union</option>
                        </select>
					</fieldset>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row">
					<label for="special_user_amount">Special User</label>
				</th>
				<td class="forminp">
					<fieldset>
                       <input type="checkbox" name="special_user_amount" value="1">(Higher Than $<?php if(!empty($sp_amount)){echo $sp_amount;}else{echo '0';}?>.)<br>
					</fieldset>
				</td>
			</tr>
            
			</table>
			<p class="submit">
				<input name="add_pic" class="button-primary submit-btn" type="submit" value="Save New">
				<input name="cancel_pic" class="button-primary cancel-btn" type="submit" value="Cancel">
			 </p>
			</form>
		  </div>
		</div>
	<?php } }

public function pic_form_styles($hook) {
	if($hook=='woocommerce_page_payment_instruction')
	wp_enqueue_script( 'wc-pic-jquery', WC_PIC_PLUGIN_URL . '/js/backend.js', array( 'jquery' ) );
	wp_enqueue_style( 'wc-pic-styles', WC_PIC_PLUGIN_URL . '/css/backend.css', null, WC_PIC_VERSION );
}


}	
$GLOBALS['wc_pic'] = new WC_PIC();
