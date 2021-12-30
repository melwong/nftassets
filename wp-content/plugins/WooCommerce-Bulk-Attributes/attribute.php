<style type="text/css">
	.bulk-main {float: left; width:800px; background: #fff; margin: 20px 0 2px 14px; padding:30px 30px; box-sizing: border-box; border: 1px solid #e5e5e5; }

	.bulk-main h1 {margin: 0 0 30px 0; line-height: normal; padding: 0 0 15px 0; border-bottom: 3px solid #e5e5e5; }

	.bulk-main h2.headingatt { margin:0 0 0 0;font-weight: 500;font-size: 18px;}

	.bulk-main h2.headingatt select#select {width: 50%; margin: 0 0 0 30px; height: 38px; border: 2px solid #dcdcdc; font-size: 16px; padding: 0 10px; }

	.bulk-main h2.headingatt select#select:focus{box-shadow: none !important;}

	.bulk-main form.box { width: 100%; box-sizing: border-box;margin: 0; padding: 0;}

	.bulk-main form.box .postbox2 {width: 100%; background: #f7f7f7; box-shadow:0 0 39px 0 rgba(0,0,0,0.04) !important; border-radius: 0 !important; box-sizing: border-box; padding: 30px 30px; border: 1px solid #e9e9e9;margin: 30px 0 0 0; }

	.bulk-main form.box .postbox2 h3 {background: none !important; margin: 0 0 25px 0 !important; text-align: center !important; font-size: 24px; line-height: normal;padding: 0 !important;}

	.bulk-main form.box .postbox2 .textarea-wrap {padding: 0 !important;margin: 0 !important;}

	.bulk-main form.box .postbox2 .textarea-wrap textarea {margin: 0 0 15px 0 !important; width: 100%; resize: none; background: #fff; border-radius: 3px; border: 1px solid #d8d8d8; padding: 10px 10px !important; box-sizing: border-box;font-size: 18px;color: #000 !important;}

	.bulk-main form.box .postbox2 .textarea-wrap label { color: #000;font-size: 18px;line-height: normal; margin: 0 0 5px 0; float: left;width: 100%;}

	.bulk-main form.box .postbox2 .textarea-wrap p {margin: 0 0 20px 0 !important;width: 100% !important; box-sizing: border-box !important; background: #fff !important; border: 1px solid #d8d8d8 !important; border-radius: 3px !important; padding: 10px 10px !important; font-size: 18px; }

	.bulk-main form.box .postbox2 .textarea-wrap select {width: 100% !important; margin: 0 0 20px 0 !important; background: #fff !important; border: 1px solid #d8d8d8 !important; border-radius: 3px !important; padding: 0 10px !important; font-size: 18px; line-height: normal !important; color: #000 !important; height: 49px; }

	.bulk-main form.box .postbox2 .textarea-wrap textarea:focus, .bulk-main form.box .postbox2 .textarea-wrap select:focus{box-shadow: none !important;}

	.bulk-main form.box .postbox2 p.submit-container { margin: 0 !important;text-align: left !important;}

	.bulk-main form.box .postbox2 p.submit-container input[type="submit"] {padding: 12px 30px !important;
    background: #ff8a8a !important; border: none !important; box-shadow: none !important; font-size:21px; font-weight: normal !important; cursor: pointer;text-shadow: none !important;text-transform: uppercase;}

	.bulk-main form.box .postbox2 p.submit-container input[type="submit"]:focus{box-shadow: none !important;outline: none !important;}

	.bulk-main .added { float: left; width: 100%; margin: 25px 0 0 0; font-size: 17px; font-weight: bold;color: #000;}

	

	


</style>


<div class="bulk-main">
<?php
echo "<h1>Bulk Add WC Attributes Terms</h1>";

$attribute_taxonomies = wc_get_attribute_taxonomies();
$taxonomy_terms = array();

$i=1;
if ( $attribute_taxonomies ) :
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
	jQuery("#select").change(function()
	{
		jQuery(this).find("option:selected").each(function(){
			
			if(jQuery(this).attr("value")=="red")
			{
				jQuery(".box").not(".red").hide();
				jQuery(".red").show();
			}
			<?php
				foreach ($attribute_taxonomies as $tax) :
				if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) :
				$taxonomy_terms[$tax->attribute_name] = get_terms( wc_attribute_taxonomy_name($tax->attribute_name), 'orderby=name&hide_empty=0' );
			?>
			else if(jQuery(this).attr("value")=="<?php echo $tax->attribute_id; ?>"){
				jQuery(".box").not(".<?php echo $tax->attribute_id; ?>").hide();
				jQuery(".<?php echo $tax->attribute_id; ?>").show();
			}
			<?php
				endif;
				endforeach;
			?>
			else
			{
				jQuery(".box").hide();
			}
		});
	}).change();
});
</script>

<?php endif; ?>	
	
   
<h2 class="headingatt">Bulk Add Attributes under 
	<select id="select">
		<option>Select Attributes</option>
		<?php
			foreach ($attribute_taxonomies as $tax) :
				if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) :
						$taxonomy_terms[$tax->attribute_name] = get_terms( wc_attribute_taxonomy_name($tax->attribute_name), 'orderby=name&hide_empty=0' );
						//echo $tax->attribute_id."=".$tax->attribute_name."<br>";
						?>
						<option value="<?php echo $tax->attribute_id; ?>"><?php echo ucfirst($tax->attribute_name); ?></option>
					   <?php
				endif;
			endforeach;
		?>
	</select>
</h2>

			
<?php
$attribute_taxonomies = wc_get_attribute_taxonomies();
$taxonomy_terms = array();

$i=1;
if ( $attribute_taxonomies ) :
    foreach ($attribute_taxonomies as $tax) :
		if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) :
			$taxonomy_terms[$tax->attribute_name] = get_terms( wc_attribute_taxonomy_name($tax->attribute_name), 'orderby=name&hide_empty=0' );
			//echo $i."=".$tax->attribute_name."<br>";

			if(isset($_POST['save'.$i]))
			{

				$textarea = $_POST['addtributes'.$i];
				//$textarea_array= explode(",",$textarea);
				
				$pos = strpos($textarea, ',');
				if ($pos === false) {
					/* echo "The string ',' was not found in the string ''.";*/
					$textarea = str_replace("\n", "/", $textarea);
					$textarea = str_replace("\r", "", $textarea);
				   
					$pos1 = strpos($textarea, '/');
					
					if ($pos1 === false) {
						/* echo "The string '/' was not found in the string ''.";*/
						 
						$pos2 = strpos($textarea, '|');
						 
						if ($pos2 === false) {
							$textarea_array[] = $textarea; 
							/*echo "not found any sepaator";*/
						}else{
							/*echo "The string '|' was found in the string '',";*/
							$textarea_array	= explode("|",$textarea);   
						}
					}else{
						/* echo "The string '/' was found in the string '',";*/
					   $textarea_array	= explode("/",$textarea);  
					}
					
				} else {
					$textarea_array	= explode(",",$textarea);
				   /* echo "The string ',' was found in the string '',";*/
				   // echo " and exists at position $pos.";
				}
				
				
				$count = count($textarea_array);
				for($j=0;$j<count($textarea_array);$j++) {
					$slug = strtolower($textarea_array[$j]);

					$wp_insert_term = wp_insert_term( $textarea_array[$j], 'pa_'.$tax->attribute_name, 
												$args = array(
												'description' => $textarea_array[$j] ,
												'slug' => $slug,
												'parent' => $_POST['parent'.$i]) 
												);
												
					if(isset($wp_insert_term))
					{
						$success = "<div class='added'>Total ".$count." Attributes are Added!</div>";
					}

				}
				
			}		
			
?>
				
		<form class="box form<?php echo $i; ?>  <?php echo $tax->attribute_id; ?>" action="" method="POST">
			
			<div class="postbox2" id="dashboard_right_now">
				<h3 style="text-align:center; background:#EEEEEE;"><?php echo ucfirst($tax->attribute_name); ?></h3>
				<div class="textarea-wrap" id="description-wrap" style="padding: 10px;">
					<label>Add Attributes:</label>
					<textarea cols="30" rows="6" class="form-field1" id="content<?php echo $i; ?>" name="addtributes<?php echo $i; ?>" ></textarea>
					<p>Add multiple attributes with comma seprated</p>
				</div>
				
				<div class="textarea-wrap" id="description-wrap" style="padding: 10px;">
					<?php   
						$taxonomy = "pa_".$tax->attribute_name;  
						$terms = get_terms($taxonomy, array(  "orderby"    => "count", "hide_empty" => false)   );
						$hierarchy = _get_term_hierarchy($taxonomy);       
					?>
					<select class="form-field1" id="parent<?php echo $i; ?>" name="parent<?php echo $i; ?>">
						<option value="0" class="level-0">Create Parent/Select Parent(create child)</option>	
						<?php			  	   
							foreach($terms as $term) 
							{	
								//Parents Attributes
								if($term->parent == 0) 
								{
											  
									echo '<option value="'.$term->term_id.'" class="level-0">'.$term->name.'</option>';
								}
								//Child Attriutes	
								if($hierarchy[$term->term_id]) 
								{              
									foreach($hierarchy[$term->term_id] as $child) 
									{              
										$child = get_term($child, "pa_".$tax->attribute_name);            						
										echo '<option value="'.$child->term_id.'" class="level-0">--'.$child->name.'</option>';		   
																		   
									}        
								}	  	 	  
							}
						?>
					</select>
					<p class="form-field1">If you want to create parent then do not select any option and if you want to create a child attribute please select any parent attribute</p>
				</div>
				
				<p class="submit-container">
					<input type="submit" value="Add Terms <?php echo $i; ?>" class="submit-button" id="save-post<?php echo $i; ?>" name="save<?php echo $i; ?>">
				</p>
			</div>

		</form>
			
<?php
		endif;
		$i++;	
	endforeach;

endif;

echo @$success;
		
?>
</div>