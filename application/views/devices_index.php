<?php $this->load->view('template_top'); ?>

    <h1 class='wi'>Devices</h1>
    <p class='intro'>The list below contains all recent syslog messages.</p>

    <table class='devices'>
        <tr>
            <th width="150">Host</th>
            <th width="100">Messages</th>
            <th width="*">Activity</th>
        </tr>
        <?php foreach($devices as $device): ?>
        <tr>
            <td valign="top"><a href="#/messages/host/<?php echo $device->FromHost; ?>"><?php echo $device->FromHost; ?></a></td>
            <td class="count" valign="top"><?php echo number_format($device->count); ?></td>
            <td valign="top"><img src="/graph/sparkline/<?php echo $device->FromHost; ?>" /></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
<?php $this->load->view('template_bottom'); ?>
