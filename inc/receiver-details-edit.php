<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$receiver_id = base64_decode($_GET['receiver']);
$select_reciver_obj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wc_payment_instructions WHERE id = $receiver_id");
$sp_amount = get_option("sp_amount");?>
<h2>Update Receiver Details</h2>
	<div class="wrap">
      <div id="faq-wrapper">
        <form method="post" action="?page=payment_instruction" class="add-form">
        <input type="hidden" name="hidden_action" value="edit"/>
        <input type="hidden" name="hidden_id" value="<?php echo $_GET['receiver'];?>"/>
        <table class="form-table">
        <tr valign="top">
			<th scope="row">
				<label for="receiver_name">Receiver Name</label>
			</th>
			<td class="forminp">
				<fieldset>
					<input class="input-text regular-input " type="text" name="receiver_name" id="receiver_name" value="<?php echo $select_reciver_obj->receiver_name;?>" placeholder="">
				</fieldset>
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">
				<label for="receiver_city">Receiver City</label>
			</th>
			<td class="forminp">
				<fieldset>
					<input class="input-text regular-input " type="text" name="receiver_city" id="receiver_city" value="<?php echo $select_reciver_obj->receiver_city;?>" placeholder="">
				</fieldset>
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">
				<label for="receiver_state">Receiver State</label>
			</th>
			<td class="forminp">
				<fieldset>
					<input class="input-text regular-input " type="text" name="receiver_state" id="receiver_state" value="<?php echo $select_reciver_obj->receiver_state;?>" placeholder="">
				</fieldset>
			</td>
		</tr>
        <tr valign="top">
				<th scope="row">
					<label for="test_question">Test Question</label>
				</th>
				<td class="forminp">
					<fieldset>
						<input class="input-text regular-input " type="text" name="test_question" id="test_question" value="<?php echo $select_reciver_obj->test_question;?>" placeholder="">
					</fieldset>
				</td>
		 </tr>
        <tr valign="top">
			<th scope="row">
				<label for="test_answer">Test Answer (Favourite Color)</label>
			</th>
			<td class="forminp">
				<fieldset>
					<input class="input-text regular-input " type="text" name="test_answer" id="test_answer" value="<?php echo $select_reciver_obj->test_answer;?>" placeholder="">
				</fieldset>
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">
				<label for="active_number">Maximum Number of Time to use a payment</label>
			</th>
			<td class="forminp">
				<fieldset>
					<input class="input-text regular-input " type="number" name="active_number" id="active_number" value="<?php echo $select_reciver_obj->active_number;?>" placeholder="">
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
                            <option value="cod" <?php if ($select_reciver_obj->payment_method=='cod') { ?>selected="selected"<?php } ?>>Money Gram</option>
                            <option value="cheque" <?php if ($select_reciver_obj->payment_method=='cheque') { ?>selected="selected"<?php } ?>>Western Union</option>
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
                       <input type="checkbox" name="special_user_amount" value="1" <?php if($select_reciver_obj->special_user_amount==1){ echo "checked='checked'";}?>>(Higher Than $<?php if(!empty($sp_amount)){echo $sp_amount;}else{echo '0';}?>.)<br>
					</fieldset>
				</td>
			</tr>
            
        </table>
        <p class="submit">
            <input name="add_pic" class="button-primary submit-btn" type="submit" value="Update">
         </p>
        </form>
      </div>
    </div>
