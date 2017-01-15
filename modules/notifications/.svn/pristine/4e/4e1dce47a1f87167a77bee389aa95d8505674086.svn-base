<?php

/*
*    Copyright 2015 Maarch
*
*  This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  schedule notifications
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->test_admin('admin_notif', 'notifications');

require_once 'modules/notifications/class/class_schedule_notifications.php';

/****************Management of the location bar  ************/
    $init = false;
    if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
        $init = true;
    }

    $level = '';
    if (isset($_REQUEST['level'])
        && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3
            || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)) {
        $level = $_REQUEST['level'];
    }

    $pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page='
               . 'schedule_notifications&module=notifications' ;
    $pageLabel = _SCHEDULE_NOTIFICATIONS;
    $pageId = "schedule_notifications";
    $ct = new core_tools();
    $ct->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/

?>
<h1>
	<i class='fa fa-clock-o fa-2x'></i>
	<?php echo _SCHEDULE_NOTIFICATIONS;?>
</h1>

<?php

$schedule = new ScheduleNotifications();

$data = $schedule->getCrontab();
$notifications = $schedule->getAuthorizedNotifications();
$flag_notif = false;

?>

<script>
	var linecount = <?php echo count($data);?>;

	function add_cronLine(){
		$("cron").innerHTML += "<tr id='row-"+linecount+"'>\
					<td>\
						<select name='data["+linecount+"][h]'>\
							<option value=\"*\">*</option>\
							<?php
							for($iHours=0;$iHours<24;$iHours++){?>\
								<option value='<?php functions::xecho($iHours);?>'\
								<?php if ($iHours == $e['h']) { ?>\
									selected=\"selected\"\
								<?php } ?>\
								>\
								<?php functions::xecho($iHours);?>\
								</option>\
							<?php } ?>\
						</select>\
					</td>\
					<td width=\"10%\">\
						<select name='data["+linecount+"][m]'>\
							<option value=\"*\">*</option>\
							<?php
							for($iMinutes=0;$iMinutes<60;$iMinutes++){ ?>\
								<option value='<?php functions::xecho($iMinutes);?>'\
								<?php if ($iMinutes == $e['m']) { ?>\
									selected=\"selected\"\
								<?php } ?>\
								>\
								<?php functions::xecho($iMinutes);?>\
								</option>\
							<?php } ?>\
						</select>\
					</td>\
					<td width=\"10%\">\
						<select name='data["+linecount+"][dom]'>\
							<option value=\"*\">*</option>\
							<?php
							for($iDay=1;$iDay<32;$iDay++){ ?>\
								<option value='<?php functions::xecho($iDay);?>'\
								<?php if ($iDay == $e['dom']) { ?>\
									selected=\"selected\"\
								<?php } ?>\
								>\
								<?php functions::xecho($iDay);?>\
								</option>\
							<?php } ?>\
						</select>\
					</td>\
					<td width=\"15%\">\
						<select name='data["+linecount+"][mon]'>\
							<option value =\"*\">*</option>\
							<?php $month_array = array(1 => _JANUARY, _FEBRUARY, _MARCH, _APRIL, _MAY, _JUNE, _JULY, _AUGUST, _SEPTEMBER, _OCTOBER, _NOVEMBER, _DECEMBER);
		                 	for($iMonth=1;$iMonth<13;$iMonth++) {
		                 		?> <option value=\"<?php functions::xecho($iMonth);?>\"\
		                 			<?php if ($iMonth == $e['mon']) { ?>\
									selected=\"selected\"\
								<?php } ?>\
								>\
								<?php functions::xecho($month_array[$iMonth]);?>\
								</option>\
		                 	<?php } ?>\
						</select>\
					</td>\
					<td width=\"20%\">\
						<select name='data["+linecount+"][dow]'>\
							<option value =\"*\">*</option>\
							<?php $weekday_array = array(1 => _MONDAY, _TUESDAY, _WEDNESDAY, _THURSDAY, _FRIDAY, _SATURDAY, _SUNDAY);
		                 	for($iWeekDay=1;$iWeekDay<8;$iWeekDay++) {
		                 		?> <option value=\"<?php functions::xecho($iWeekDay);?>\"\
		                 			<?php if ($iWeekDay == $e['dow']) { ?>\
									selected=\"selected\"\
								<?php } ?>\
								>\
								<?php functions::xecho($weekday_array[$iWeekDay]);?>\
								</option>\
		                 	<?php } ?>\
						</select>\
					</td>\
					<td width=\"40%\">\
						<select style=\"width:100%\" name='data["+linecount+"][cmd]'>\
							<option><?php echo _CHOOSE_NOTIF;?></option>\
						<?php foreach ($notifications as $key => $value) {
								?><option value=\"<?php functions::xecho($key);?>\"\
		                 			<?php if ($key == $e['cmd']) { ?>\
										selected=\"selected\"\
									<?php } ?>\
									>\
								<?php functions::xecho($value);?>\
								</option>\
						<?php } ?>\
						</select></td>\
					<td width=\"20px\" align=\"center\">\
						<input type='hidden' id='state-"+linecount+"' name='data["+linecount+"][state]' value='new' />\
						<i class='fa fa-remove fa-1x' onclick='del("+linecount+");' onmouseover=\"this.style.cursor='pointer'\" ></i>\
					</td>\
				</tr>";
		linecount++;
		$('no_notif').style.display="none";
	}
</script>
<div class="block" style="margin-top:15px;">
<br/>
<br/>
<?php echo _HELP_CRON;?>
<br/>
<br/>
<div class='crontab'>
	<form method='POST' class="forms" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=notifications&page=schedule_notifications_controler">
		<table id='cron'>
			<tr>
				<td style="color:#16adeb"><?php echo _HOUR ?></td>
				<td width="10%" style="color:#16adeb"><?php echo _MINUTE ?></td>
				<td width="10%" style="color:#16adeb"><?php echo _DAY ?></td>
				<td width="15%" style="color:#16adeb"><?php echo _MONTH ?></td>
				<td width="20%" style="color:#16adeb"><?php echo _WEEKDAY ?></td>
				<td width="40%" style="color:#16adeb"><?php echo _NOTIF_DESCRIPTION ?></td>
				<td></td>
			</tr>
		<?php foreach ($data as $id => $e) { 

				if ($e['state'] == "hidden") {?>
					<tr id='row-<?php functions::xecho($id);?>' style="display:none">
						<td>
							<input name='data[<?php functions::xecho($id);?>][h]' value="<?php functions::xecho($e['h']);?>">
						</td>
						<td width="10%">
							<input name='data[<?php functions::xecho($id);?>][m]' value="<?php functions::xecho($e['m']);?>">
						</td>
						<td width="10%">						
							<input name='data[<?php functions::xecho($id);?>][dom]' value="<?php functions::xecho($e['dom']);?>">
						</td>
						<td width="15%">
							<input name='data[<?php functions::xecho($id);?>][mon]' value="<?php functions::xecho($e['mon']);?>">
						</td>
						<td width="20%">
							<input name='data[<?php functions::xecho($id);?>][dow]' value="<?php functions::xecho($e['dow']);?>">
						</td>
						<td width="40%">
							<input name='data[<?php functions::xecho($id);?>][cmd]' value="<?php functions::xecho($e['cmd']);?>">
						</td>
						<td width="20px" align="center">
							<input type='hidden' id='state-<?php functions::xecho($id);?>' name='data[<?php functions::xecho($id);?>][state]' value='hidden' />
						</td>
					</tr>
				<?php
				} else {
					$flag_notif = true;

			?>
				<tr id='row-<?php functions::xecho($id);?>'>
					<td>
						<select name='data[<?php functions::xecho($id);?>][h]'>
							<option value="*">*</option>
							<?php
							for($iHours=0;$iHours<24;$iHours++){?>
								<option value='<?php functions::xecho($iHours);?>'
								<?php if ($iHours == $e['h']) { ?>
									selected="selected"
								<?php } ?>
								>
								<?php functions::xecho($iHours);?>
								</option>
							<?php } ?>
						</select>
					</td>
					<td width="10%">
						<select name='data[<?php functions::xecho($id);?>][m]'>
							<option value="*">*</option>
							<?php
							for($iMinutes=0;$iMinutes<60;$iMinutes++){ ?>
								<option value='<?php functions::xecho($iMinutes);?>'
								<?php if ($iMinutes == $e['m']) { ?>
									selected="selected"
								<?php } ?>
								>
								<?php functions::xecho($iMinutes);?>
								</option>
							<?php } ?>
						</select>
					</td>
					<td width="10%">						
						<select name='data[<?php functions::xecho($id);?>][dom]'>
							<option value="*">*</option>
							<?php
							for($iDay=1;$iDay<32;$iDay++){ ?>
								<option value='<?php functions::xecho($iDay);?>'
								<?php if ($iDay == $e['dom']) { ?>
									selected="selected"
								<?php } ?>
								>
								<?php functions::xecho($iDay);?>
								</option>
							<?php } ?>
						</select>
					</td>
					<td width="15%">
						<select name='data[<?php functions::xecho($id);?>][mon]'>
							<option value ="*">*</option>
							<?php $month_array = array(1 => _JANUARY, _FEBRUARY, _MARCH, _APRIL, _MAY, _JUNE, _JULY, _AUGUST, _SEPTEMBER, _OCTOBER, _NOVEMBER, _DECEMBER);
		                 	for($iMonth=1;$iMonth<13;$iMonth++) {
		                 		?> <option value="<?php functions::xecho($iMonth);?>" 								
		                 			<?php if ($iMonth == $e['mon']) { ?>
									selected="selected"
								<?php } ?>
								>
								<?php functions::xecho($month_array[$iMonth]);?>
								</option>
		                 	<?php } ?>
						</select>
					</td>
					<td width="20%">
						<select name='data[<?php functions::xecho($id);?>][dow]'>
							<option value ="*">*</option>
							<?php $weekday_array = array(1 => _MONDAY, _TUESDAY, _WEDNESDAY, _THURSDAY, _FRIDAY, _SATURDAY, _SUNDAY);
		                 	for($iWeekDay=1;$iWeekDay<8;$iWeekDay++) {
		                 		?> <option value="<?php functions::xecho($iWeekDay);?>" 								
		                 			<?php if ($iWeekDay == $e['dow']) { ?>
									selected="selected"
								<?php } ?>
								>
								<?php functions::xecho($weekday_array[$iWeekDay]);?>
								</option>
		                 	<?php } ?>
						</select>
					</td>
					<td width="40%">
						<select style="width:100%" name='data[<?php functions::xecho($id);?>][cmd]'>
							<option><?php echo _CHOOSE_NOTIF;?></option>
						<?php foreach ($notifications as $key => $value) {
								?><option value="<?php functions::xecho($key);?>"
		                 			<?php if ($key == $e['cmd']) { ?>
										selected="selected"
									<?php } ?>
									>
								<?php functions::xecho($value);?>
								</option>
						<?php } ?>
						</select>
					</td>
					<td width="20px" align="center">
						<input type='hidden' id='state-<?php functions::xecho($id);?>' name='data[<?php functions::xecho($id);?>][state]' value='normal' />
						<i class='fa fa-remove fa-2x' onclick='del(<?php functions::xecho($id);?>);' onmouseover="this.style.cursor='pointer'"></i>
					</td>
				</tr>
		<?php
			}
		} ?>
		</table>
		<span <?php if($flag_notif){?> style="display:none" <?php } ;?> id="no_notif">
			<i><?php echo _NO_NOTIF;?></i>
		</span><br/>
		<i class='fa fa-plus-square fa-2x' onclick='add_cronLine();' onmouseover="this.style.cursor='pointer'"></i>
		<br />
		<br />
		<input type='submit' value='<?php echo _VALIDATE;?>' name='save' class="button" />
		<input type="button" class="button" onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_notifications_controler&mode=list&module=notifications'" value="<?php echo _CANCEL;?>" name="cancel">
	</form>
</div>
</div>