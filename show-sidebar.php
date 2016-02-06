<?php
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

$search_location = isset($_POST['show_location'])?$_POST['show_location']:'';
$search_startdate_get = isset($_POST['start_date'])?$_POST['start_date']:'';
$search_startdate  = strtotime($search_startdate_get);
$search_enddate_get = isset($_POST['end_date'])?$_POST['end_date']:'';
$search_enddate  = strtotime($search_enddate_get);
$search_management = isset($_POST['show_management'])?$_POST['show_management']:'';
$search_horsename = isset($_POST['h_name'])?$_POST['h_name']:'';

$sql="SELECT presented_by,name_show FROM horseshows";
$show_management = $wpdb->get_results($sql);
//var_dump($show_management);
?>
<style type="text/css">
.tabs {
      width: 94%;
	  display: inline-block;
	  background-color: #fff;
	  margin: 3%;
	  border-radius:8px;
}
 
    /*----- Tab Links -----*/
    /* Clearfix */
    .tab-links:after {
        display:block;
        clear:both;
        content:'';
    }
 
    .tab-links li {
        margin:0;
        float:left;
        list-style:none;
		background:none !important;
		border-bottom:none !important;
	    padding: 7px 5px 0 15px !important;
	    text-shadow:none !important;
    }
 
        .tab-links a {
            padding:9px 15px;
            display:inline-block;
            border-radius:3px 3px 0px 0px;
            background:#9D0707;
            font-size:16px;
            font-weight:600;
            color:#fff;
            transition:all linear 0.15s;
        }
 
        .tab-links a:hover {
            background:#9D0707;
            text-decoration:none;
        }
 
    li.active a, li.active a:hover {
          /*background: #252424;*/
  color: #FFFFFF;
    }
 
    /*----- Content of Tabs -----*/
    .tab-content {
        margin:0 15px 15px 15px;
		padding:10px 0 10px 0;
        border-radius:none;
        box-shadow:-1px 1px 1px rgba(0,0,0,0.15);
        background:#EFEFEF;
    }
	.tab-content table {
		width:96%;
		margin: 2%;  
		border: 5px solid #FFF;
  		background-color: #fff;
	}
 
        .tab {
            display:none;
        }
 
        .tab.active {
            display:block;
        }
		.pad5{
			padding:5px;
			width:20%;
		}
		.month_hs{
			font-size: 16px;
			  padding: 5px 0;
			  text-align: center;
			  font-weight: 600;
		}
		.date_hs{
			text-align: center;
			  font-size: 28px;
			  font-weight: 700;
			  padding:0;
		}
		.day_hs{
			font-size: 16px;
			  padding: 5px 0;
			  text-align: center;
			  font-weight: 600;
			  color:#999;
		}
		.td_hs{
			  background-color: #ccc;
  vertical-align: middle;
		}
		.tr_hs{
			padding-bottom:10px;
			border-bottom:1px solid #FFF;
		}
		.second p{
			padding: 0 0 0 10px;
		}
		.more_info{
		    padding: 5px;
			background-color: #2271BE;
			color: #fff;
			line-height: 15px;
			border-radius: 5px;
			float: right;
		}
		.tab-links li.active a,.tab-links li.active a:hover {
    background: #252424;
    color: #FFFFFF;
}
		
</style>
<div class="content_right">
    <div class="top_block_advance_search">
        <div class="search_ad_block">
            <div class="refine_icon"></div>
            <div class="advance_search_box_content">
            <form name="" method="post">
                <h1 class="header_name_refine">Refine Horse Show Search</h1>
                <input  type="text" placeholder="Location" name="show_location" value="<?php echo $search_location; ?>" />
                <label>
               
                <select name="show_management">
                    <option selected value="">Show Management Companies</option>
                    <?php foreach($show_management as $management){ 
                    ?>
                   <option value="<?php echo $management->name_show; ?>" <?php if($search_management==$management->name_show){ echo 'selected="selected"'; } ?>><?php echo $management->name_show; ?></option>
                    <?php } ?>
                </select>
                </label>
                <!--<label>Dates</label>-->
                <label> <input type="text" placeholder="Start date" name="start_date" value="<?php echo $search_startdate_get; ?>" class="date"/></label>
                <label><input type="text" placeholder="End date" name="end_date" class="date" value="<?php echo $search_enddate_get; ?>" /></label>
                <!--<label>Horse Name</label>-->
                <label><input  type="text" placeholder="Horse Name" name="h_name" value="<?php echo $search_horsename; ?>" /></label>
                <!--<a href="#">POSTED SEARCHES</a>-->
                <input type="submit" class="width30" value="Search" name="show_search" />
            </form>
                
        </div><!--/search_ad_block-->
    </div>
        
</div>
<div class="tabs">
    <ul class="tab-links">
        <li class="active"><a href="#tab1">Upcoming</a></li>
        <li><a href="#tab2">Just Announced</a></li>
    </ul>
                                     
    <div class="tab-content">
        <div id="tab1" class="tab active">
         <table>
         <tr>
            <!--<td class="pad5">Date</td>
            <td class="pad5">Event</td>
            <td class="pad5"></td>-->
            <td class="pad5"></td>
            <td class="pad5"></td>
            <td class="pad5"></td>
         </tr>
          
            <?php 
        $sql="SELECT *FROM horseshows";
        $results = $wpdb->get_results($sql);
        $i=0;
        foreach($results as $result)
        {
        if($i<2)
        {
        ?>
          <tr class="tr_hs">
          <td class="td_hs">
          <?php 
		  //code correctly working with date and time
          /*?>$show_date_get =  $result->show_date_time; 
		  $show_date_split_to = explode(" to ",$show_date_get);
		  $show_date_split_space = explode(" ",$show_date_split_to[0]);
		  $date=date_create($show_date_split_space[0]);
		  $dateconverted=date_format($date,"Y/M/D");
		  $dateandtime=date_format($date,"d/m/Y");
		  $datesplitbyslash=explode("/",$dateconverted);
		  $show_date = date("d/m/Y", $dateconverted);
          ?>
          <p class="month_hs"><?php echo $datesplitbyslash[1];?></p>
          <p class="date_hs" style="font-size: 10px;"><?php echo $dateandtime.' '.$show_date_split_space[1];?></p>
          <p class="day_hs"><?php echo $datesplitbyslash[2];?></p>
          <?php */?>
          
          <?php
          $show_date_get =  $result->show_date_time; 
		  //var_dump($show_date_get);
		  $show_date_split_to = explode(" to ",$show_date_get);
		  $show_date_split_space = explode(" ",$show_date_split_to[0]);
		  $date=date_create($show_date_split_space[0]);
		  $dateconverted_month_date=date_format($date,"M/d/Y");
		  $dateconverted_M_D_Y=date_format($date,"m/d/Y");
		  //$dateconverted=date_format($date,"Y/M/D");
		  //$dateandtime=date_format($date,"d/m/Y");
		  //$datesplitbyslash=explode("/",$dateconverted);
		  $datesplitbyslash_month_date=explode("/",$dateconverted_month_date);
		  //$show_date = date("d/m/Y", $dateconverted);
          ?>
          <?php if($show_date_get!=" to "){?>
          <p class="month_hs"><?php echo $datesplitbyslash_month_date[0].' '.$datesplitbyslash_month_date[1];?></p>
          <p class="date_hs" style="font-size: 10px;"><?php echo $dateconverted_M_D_Y;?></p>
          <?php /*?><p class="day_hs"><?php echo $datesplitbyslash[2];?></p><?php */?>
          
          
          <?php } ?>
          </td>
          <td class="second"><p><?php echo $result->name_show;?></p>
          <p><?php echo $result->presented_by;?></p>
          <p><?php echo $result->city.",".$result->state;?></p>
          </td>
          <td><a class="more_info" href="<?php echo site_url();?>/show-details/?id=<?php echo $result->id; ?>">MORE INFO</a></td>
          </tr>
        <?php $i++; } }?>
         </table>
    
        </div>
        <div id="tab2" class="tab">
            <table>
           <tr>
            <td class="pad5"></td>
            <td class="pad5"></td>
            <td class="pad5"></td>
         </tr>
          
            <?php 
        $sql="SELECT *FROM horseshows";
        $results = $wpdb->get_results($sql);
        $i=0;
        foreach($results as $result)
        {
        if($i<2)
        {
        ?>
          <tr class="tr_hs">
          <td class="td_hs">
          <?php /*?>
          $show_date_get =  $result->show_date_time; 
		  
		  $show_date_split_to = explode(" to ",$show_date_get);
		  $show_date_split_space = explode(" ",$show_date_split_to[0]);
		  $date=date_create($show_date_split_space[0]);
		  $dateconverted=date_format($date,"Y/M/D");
		  $dateandtime=date_format($date,"d/m/Y");
		  $datesplitbyslash=explode("/",$dateconverted);
		  $show_date = date("d/m/Y", $dateconverted);<?php */?>
		  
		  
		  <?php
		  
          /* not used
		  $show_date = date("d-m-Y", $show_date_get);
          $show_date_split = explode("-",$show_date);
          $time=strtotime($show_date);
          $month=date("F",$time);
          $string=substr($month, 0,3);
          $show_date_split[0];
          $day=date("D",$time);*/
          ?>
          <?php /*?><p class="month_hs"><?php echo $string;?></p>
          <p class="date_hs"><?php echo $show_date_split[0];?></p>
          <p class="day_hs"><?php echo $day;?></p><?php */?>
          <?php /*?><p class="month_hs"><?php echo $datesplitbyslash[1];?></p>
          <p class="date_hs" style="font-size: 10px;"><?php echo $dateandtime.' '.$show_date_split_space[1];?></p>
          <p class="day_hs"><?php echo $datesplitbyslash[2];?></p><?php */?>
          
          <?php
          $show_date_get =  $result->show_date_time; 
		  //var_dump($show_date_get);
		  $show_date_split_to = explode(" to ",$show_date_get);
		  $show_date_split_space = explode(" ",$show_date_split_to[0]);
		  $date=date_create($show_date_split_space[0]);
		  $dateconverted_month_date=date_format($date,"M/d/Y");
		  $dateconverted_M_D_Y=date_format($date,"m/d/Y");
		  //$dateconverted=date_format($date,"Y/M/D");
		  //$dateandtime=date_format($date,"d/m/Y");
		  //$datesplitbyslash=explode("/",$dateconverted);
		  $datesplitbyslash_month_date=explode("/",$dateconverted_month_date);
		  //$show_date = date("d/m/Y", $dateconverted);
          ?>
          <?php if($show_date_get!=" to "){?>
          <p class="month_hs"><?php echo $datesplitbyslash_month_date[0].' '.$datesplitbyslash_month_date[1];?></p>
          <p class="date_hs" style="font-size: 10px;"><?php echo $dateconverted_M_D_Y;?></p>
          <?php /*?><p class="day_hs"><?php echo $datesplitbyslash[2];?></p><?php */?>
          
          
          <?php } ?>
          </td>
          
          <td class="second"><p><?php echo $result->name_show;?></p>
          <p><?php echo $result->presented_by;?></p>
          <p><?php echo $result->city.",".$result->state;?></p>
          </td>
          <td><a class="more_info"  href="<?php echo site_url();?>/show-details/?id=<?php echo $result->id; ?>">MORE INFO</a></td>
          </tr>
        <?php  $i++; } }?>
         </table>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function() {
jQuery('.date').datepicker({
dateFormat : 'dd-mm-yy'
});
});
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
 
        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });
});
</script>

