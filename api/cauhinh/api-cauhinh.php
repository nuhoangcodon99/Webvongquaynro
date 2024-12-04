<?php
require_once '../../core/cauhinh.php';

function getCPUUsage()
{
    $cpuUsage = shell_exec('wmic cpu get loadpercentage /value');
    if (strpos($cpuUsage, '=') !== false) {
        $cpuUsage = explode('=', $cpuUsage)[1];
    } else {
        // Xử lý lỗi khi không tìm thấy ký tự '=' trong chuỗi
    }
    return trim($cpuUsage);
}

function getRAMUsage()
{
    $ramInfo = shell_exec('wmic OS get TotalVisibleMemorySize, FreePhysicalMemory /value');
    $ramInfo = explode(PHP_EOL, $ramInfo);

    $totalRAM = '';
    $freeRAM = '';

    foreach ($ramInfo as $info) {
        if (strpos($info, '=') !== false) {
            $data = explode('=', $info);
            if ($data[0] === 'TotalVisibleMemorySize') {
                $totalRAM = $data[1];
            } elseif ($data[0] === 'FreePhysicalMemory') {
                $freeRAM = $data[1];
            }
        }
    }

    $ramUsage = 0;
    if ($totalRAM != 0) {
        $ramUsage = (($totalRAM - $freeRAM) / $totalRAM) * 100;
    } else {
        // Xử lý lỗi khi $totalRAM = 0
    }

    return round($ramUsage, 2);
}

function getSSDUsage()
{
    $driveLetter = 'C:';
    $ssdTotal = disk_total_space($driveLetter); // Dung lượng tổng ổ đĩa C
    $ssdFree = disk_free_space($driveLetter); // Dung lượng trống của ổ đĩa C

    if ($ssdTotal !== false && $ssdTotal > 0) {
        $ssdUsed = $ssdTotal - $ssdFree;
        $ssdUsedGB = round($ssdUsed / (1024 * 1024 * 1024), 0); // Dung lượng SSD đã sử dụng (làm tròn)

        return $ssdUsedGB;
    } else {
        return 0;
    }
}

function getSSDTotal()
{
    $driveLetter = 'C:';
    $ssdTotal = disk_total_space($driveLetter); // Dung lượng tổng ổ đĩa C

    if ($ssdTotal !== false && $ssdTotal > 0) {
        $ssdTotalGB = round($ssdTotal / (1024 * 1024 * 1024), 0); // Dung lượng SSD tổng (làm tròn)

        return $ssdTotalGB;
    } else {
        return 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'config') {
        $cpuUsage = getCPUUsage();
        $ramUsage = getRAMUsage();
        $ssdUsage = getSSDUsage();
        $ssdTotal = getSSDTotal(); // Tổng dung lượng ổ C

        $configData = [
            'cpuUsage' => $cpuUsage,
            'ramUsage' => $ramUsage,
            'ssdUsage' => $ssdUsage,
            'ssdTotal' => $ssdTotal
        ];

        header('Content-Type: application/json');
        echo json_encode($configData);
        exit;
    } else {
        header('HTTP/1.1 400 Bad Request');
        echo 'Invalid action.';
        exit;
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid request method.';
    exit;
}

?>