<!DOCTYPE html>
<html>
<head>
    <title>Xdebug Trace Explorer</title>
    <style type="text/css">
        body {font-family: Arial}
        .hidden {display:none}
        .fn-tree {padding-left:60px;padding-right:80px}
        .fn-tree li {list-style: none}
        .fn-sub {padding-left: 20px}
        .fn-id {font-weight:bold;position: absolute;left:0;width:50px;text-align: right}
        .fn-time {position:absolute;right:0}
        .fn-file {color:#666}
        .fn-name {color:blue}
        .fn-line {cursor:hand;cursor:pointer;margin:8px 0}
        .fn-line:hover {background:lightcyan}
        .fn-params {font-weight:normal;color:#666}
        .search-highlight { border-bottom: 1px solid black;}
    </style>
</head>

<body>
<h3>Xdebug Trace Explorer</h3>
<p>Read <a href="https://github.com/tungbi/xdebug-trace-explorer/blob/master/README.md" target="_blank">README.md</a> for
    more
information</p>

<form id="frm">
    Trace file path (*.xt):<br/>
    <input type="text" id="filePath" name="filePath" style="width:400px" value="<?php if(isset
    ($traceFile))
        echo
    $traceFile?>"/> <input type="submit" value="Render"/>
    <a href="?action=go_to_last">Go to last file</a>
</form>
<?php if (count($traceFiles)>0):?>
    <p>or pick from lists we found at <?php echo $traceFolder?>:<br/>
    <select id="xt-select">
        <?php foreach ($traceFiles as $f):?>
            <option value="<?php echo "$traceFolder/$f"?>"<?php if ($traceFile == "$traceFolder/$f") echo 'selected'?>><?php echo $f?></option>        <?php endforeach?>
    </select>
    </p>
<?php endif;?>


<?php if ($traceFile != ''):?>
    <hr>
    <form id="search">
        <input placeholder="class or method name" type="text" name="search" style="width: 60%">
        <input type="submit">
        <button id="restore-hidden">Restore</button>
    </form>
    <ul class="fn-tree">
    <?php $traceExplorer->render()?>
    </ul>
<?php endif;?>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.fn-line').click(function(){
            var $expand = $(this).find('> .fn-expand');
            var fnId = $expand.attr('id');
            $('#sub-'+fnId).toggleClass('hidden');
            $expand.html($('#sub-'+fnId).hasClass('hidden')?'[+] ':'[-] ');
        });

        $('#xt-select').change(function() {
            $('#filePath').val($('#xt-select').val());
            $('#frm').submit();
        });

        $('#search').submit(function (e) {
            e.preventDefault();
            var text = $(this).find('[name="search"]').val();
            if (!text) {
                return false;
            }
            var $match = $(".fn-name:contains(" + text + ")");
            if ($match.length > 0) {
                storeStatus();
                $('.fn-sub').addClass('hidden');
                $('.search-highlight').removeClass('search-highlight');
                $match.parents('.fn-sub').removeClass('hidden');
                $match.closest('.fn-line').addClass('search-highlight');
                resetToggleStatus();
            }
            return false;
        });

        $('#restore-hidden').click(function (e) {
            e.preventDefault();
            restoreStatus();
            return false;
        });

        function storeStatus() {
            $('.fn-sub').each(function () {
                $(this).data('isHidden', $(this).is('.hidden'));
            });
        }

        function restoreStatus() {
            $('.fn-sub').each(function () {
                if ($(this).data('isHidden')) {
                    $(this).addClass('hidden');
                }
            });
            resetToggleStatus();
            $('.search-highlight').removeClass('search-highlight');
        }

        function resetToggleStatus() {
            $('.fn-sub').each(function () {
                var id = $(this).attr('id').split('-').pop();
                if ($(this).is('.hidden')) {
                    $('#fn-' + id).html('[+] ');
                } else {
                    $('#fn-' + id).html('[-] ');
                }
            })
        }
    });
</script>

</body>
</html>