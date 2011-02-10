<?php $this->load->view('template_top'); ?>

    <h1 class='wi'>Log History</h1>
    <center><img src="<?php echo $graph; ?>" width="1100" height="200" alt="<?php echo $title; ?>" /></center>
    
    <h1 class='wi'><?php echo $title; ?></h1>
    <p class='intro'>The list below contains all recent syslog messages.</p>
    
    <table class='workers'>
        <tr>
            <th width="150">Date</th>
            <th width="100">Host</th>
            <th width="100">Tag</th>
            <th>Severity</th>
            <th width="*">Message</th>
        </tr>
        <?php foreach($logs as $log): ?>
        <tr>
            <td valign="top">
                <?php
                    $system_timezone = new DateTimeZone('UTC');
                    $user_timezone = new DateTimeZone('EST');
                    $date = new DateTime(date('Y-m-d H:i:s', mysql_to_unix($log->ReceivedAt)), $system_timezone);
                    $offset = $user_timezone->getOffset($date);
                    echo date('Y-m-d H:i:s', $date->format('U') + $offset);
                ?>
            </td>
            <td valign="top"><a href="#/messages/host/<?php echo $log->FromHost; ?>"><?php echo $log->FromHost; ?></a></td>
            <td valign="top"><?php echo str_replace(':', '', $log->SysLogTag); ?></td>
            <td valign="top">
                <center>
                    <?php
                        $class = 'error';
                        switch ($log->Priority) {
                            case '0':
                            case '1':
                            case '2':
                            case '3':
                            case '4':
                            case '5':
                                $class = 'error';
                                break;
                            case '6':
                            case '7':
                                $class = 'info';
                                break;
                        }
                    ?>
                    <a class="<?php echo $class; ?>-tag" href="#/messages/priority/<?php echo $log->Priority; ?>"><?php echo $priorities[$log->Priority]; ?></a>
                </center>
            </td>
            <td><?php echo $log->Message; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <div class="">
        <?php echo $this->pagination->create_links(); ?>
    </div>
    
<?php $this->load->view('template_bottom'); ?>
