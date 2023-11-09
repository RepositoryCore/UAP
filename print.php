<?php
    require_once('authenticate.php');
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $document_id = $_GET['document_id'];

    try {
        $sql = "SELECT * FROM [tuc].[Defbars_TransList_Summary] WHERE document_id = '$document_id'";
        $stmt = $obj_admin->db->prepare($sql);
        $stmt->execute();

        $summary = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
    


$output = '
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                #customers {
                    font-family: Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 11px;
                }
                #customers td, #customers th {
                    border: 1px solid #ddd;
                    padding: 3px;
                }
                #customers th {
                    padding-top: 8px;
                    padding-bottom: 8px;
                    text-align: center;
                    background-color: #fff;
                    color: #000;
                }
                .center {
                    text-align: center;
                    font-family: Arial, Helvetica, sans-serif;
                }
            </style>
        </head>

        <body>
            <div class="left">
                <h3>G-33 DEFORMED BAR x 6.00M @ 9.00M -  MANILA</h3>
            </div>';

        $output .= '
            <table id="customers" style="text-align: center;">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Percentage</th>
                        <th>10mm</th>
                        <th>12mm</th>
                        <th>16mm</th>
                        <th>20mm</th>
                        <th>25mm</th>                       
                    </tr>
                </thead>
                <tbody>';

                foreach ($summary as $row) { 
                    if ($row != null) {
                        $bgColor = $row['percentage'] == NULL ? '' : '#39698a';
                        $textColor = $row['percentage'] == NULL ? '' : '#fff';

                        $output .= '
                            <tr>
                                <td style="font-weight: bold; background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . ($row["description"]. '%') . '</td>
                                <td class="" style="font-weight: bold; background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . ($row["percentage"] ?? '') . '</td>
                                <td class="" style="font-weight: bold; text-align: right; background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . $row["Base10mm"] . '</td>
                                <td class="" style="font-weight: bold; text-align: right; background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . $row["Base12mm"] . '</td>
                                <td class="" style="font-weight: bold; text-align: right; background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . $row["Base16mm"] . '</td>
                                <td class="" style="font-weight: bold; text-align: right; background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . $row["Base20mm"] . '</td>
                                <td class="" style="font-weight: bold; text-align: right; background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . $row["Base25mm"] . '</td>
                            </tr>';
                        }
                    }
                
                $output .= '
                    </tbody>
                    </table> <br/>
                    
                    
                   <i>Note: This Printed Defbar price computation is Approved by: HCC & MYC</i> ';
                

    require 'vendor/autoload.php';

    use Dompdf\Dompdf;
    use Dompdf\Options;

    $dompdf = new Dompdf;
    $options = new Options;
    $options->setChroot(__DIR__);
    $options->setIsRemoteEnabled(true);

    $dompdf = new Dompdf($options);

    $dompdf->setPaper("LEGAL", "portrait");
    $dompdf->loadHtml($output);

    $dompdf->render();

    $dompdf->stream("Price.pdf", ["Attachment" => 0]);

?>