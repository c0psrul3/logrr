<?php $this->load->view('template_top'); ?>

    <h1 class='wi'>Inputs</h1>
    <p class='intro'>The list below contains all recent syslog messages.</p>

    <table class='devices'>
        <tr>
            <th width="150">Input</th>
            <th width="100">Messages</th>
            <th width="*">Activity</th>
        </tr>
        <?php foreach($inputs as $input): ?>
        <tr>
            <td valign="top"><a href="#/messages/input/<?php echo $input->SysLogTag; ?>"><?php echo $input->SysLogTag; ?></a></td>
            <td class="count" valign="top"><?php echo number_format($input->count); ?></td>
            <td valign="top">TBD</td>
        </tr>
        <?php endforeach; ?>
    </table>
    
<?php $this->load->view('template_bottom'); ?>
