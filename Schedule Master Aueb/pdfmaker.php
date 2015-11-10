<?php
require_once('pdf/config/lang/eng.php');
require_once('pdf/tcpdf.php');
require_once("include/global.php");
$semester= 0 + $_POST['semester'];
$department= 0 + $_POST['dep_id'];
$schedule= 0 + $_POST['schedule'];
$text =  printSchedulePDF($schedule,$department,$semester);
$depname = getdepname($department);
$sem = getsem($semester);
$sch = getsch($schedule);
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// Logo
		$this->Image('styles/images/aueb.jpg', 10, 8, 15);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(30, 10, '', 0, 0, 'C');
		// Line break
		$this->Ln(20);
	}

	// Page footer
	public function Footer() {
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
	}
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Schedule Master Aueb');
$pdf->SetTitle('Aueb Schedule');
$pdf->SetSubject('Automatic Schedule ');
$pdf->SetKeywords('');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set font
$pdf->SetFont('dejavusans', '', 12);

// add a page
$pdf->AddPage();

$pdf->SetFontSize(12);


$htmlcontent2_a = '<html>
		<center><h3>Ωρολόγιο Πρόγραμμα Ο.Π.Α. - '.$sch.'</h3><center>
		
		
		Το παρόν πρόγραμμα δημιουργήθηκε από το Schedule Master Aueb σύμφωνα με τα στοιχεία που κατέθεσε ο χρήστης.
		<br /><br/><center><b>';
$htmlcontent2_b = $depname;
//$htmlcontent2_c = '<br/>';
$htmlcontent2_c = $sem;
$htmlcontent2_d = '</b></center><br /></html>';
$htmlcontent2 = $htmlcontent2_a.$htmlcontent2_b.$htmlcontent2_c.$htmlcontent2_d;
$pdf->WriteHTML($htmlcontent2, true, 0, true, 0);

$htmlcontent2 = $text;
$pdf->WriteHTML($htmlcontent2, true, 0, true, 0);



//Close and output PDF document
$pdf->Output('schedule.pdf', 'I');

function getdepname($dep_id)
{
	global $db;
	$lessons = $_POST['lessons'];
	if($dep_id == 0)
	{ return "Όλα τα τμήματα".'<br/>'; }
	else
	{
		$res = $db->query("SELECT title FROM Department WHERE id=".$dep_id."");
		/*$res = $db->query("SELECT department_id,Department.title AS title,count(department_id) AS counter 
					   		FROM Lesson 
							INNER JOIN Department ON Department.id=Lesson.department_id
							WHERE Lesson.id IN(" .  implode(",",$lessons) . " GROUP BY department_id ORDER BY counter DESC LIMIT 1");*/
		$row = mysql_fetch_array($res);
		$onoma = $row['title'];
		return "Τμήμα: ".$onoma.'<br/>';
	}
}
function getsem($semester)
{
	
	if($semester==0)
	{ return "Όλα τα εξάμηνα";}
	else
	{
	    return "Εξάμηνο: ".$semester."ο";
	
	}
}

function getsch($schedule)
{
	global $db;
	$res = $db->query("SELECT title FROM Schedule where id = ".$schedule."");
	$row = mysql_fetch_array($res);
	$title = $row['title'];
	return $title;

}
function printSchedulePDF($schedule,$department,$semester)
{
	global $db;
	$lessons = $_POST['lessons'];
	if(is_array($lessons))
	{
		$db->query("set character set utf8");
		$res = $db->query("SELECT
								Lesson.id AS lesid,
								Submit.id,
								Submit.comments,
								Lesson.semester,
								Lesson.department_id,
								Lesson.title AS lesTitle,
								Lesson.professor as prof,
								rom.title AS romTitle,
								slot_id
							FROM
								Submit

							INNER JOIN Room rom ON rom.id=Submit.room_id
							INNER JOIN Lesson ON Lesson.id=lesson_id 
							WHERE 
								Lesson.id IN (" . implode(",",$lessons) . ")
								AND
								schedule_id=" . $schedule ."");

		$cells=array();
		while($row = mysql_fetch_array($res))
		{
			if(!is_array($cells[$row['id']]))
			$cells[$row['id']] = array();
			$entry=array();
			$entry['id']= $row['id'];
			$entry['lesson']= $row['lesTitle'];
			$entry['lesid']= $row['lesid'];
			$entry['room']= $row['romTitle'];
			$entry['department_id']= $row['department_id'];
			$entry['semester']= $row['semester'];
			$entry['comments']= $row['comments'];
			$entry['professor']= $row['prof'];
			$cells[$row['slot_id']][]= $entry;
		}
		$text = printGridPDF("printScheCellPDF",$cells);
		return $text;
	}


}



function printScheCellPDF($slot_id,$cell)
{
	global $schedule;
	$text = '';
	$text .= '<td>';
	if(is_array($cell))
	{
		$semesters = array();
		$text .= '<table border="1" cellspacing="2">';
		foreach($cell AS $entry)
		{
			$semester=$entry['semester'];

			$text .= '<tr>';
			$text .= '<td bgcolor="#f5deb3" align="center" >';
			$text .= '<span style="font-size: x-small;" >';
			$text .= '<small>';
			$text .= '' . $entry['lesson'] . '<br/>';
			$text .= '<span style="text-align:center;font-weight: x-small;">' . $entry['professor'] . '</span><br/>';
			$text .= '<span style="text-align:center;font-weight: bold;">' . $entry['room'] . '</span>';
			if(strlen($entry['comments'])>0)
				$text .= '<br/><span style="text-align:center;font-weight: normal;font-style:italic;">' . $entry['comments'] . '</span>';
			
			
			$text .= '</small>';
			//$text .= '<br/>';
			$text .= '</span>';
			$text .= '</td>';
			$text .= '</tr>';
		}
		$text .= '</table>';
	}
	$text .= '</td>';

	return $text;
}






function printGridPDF($PrintCellFunction,$cells)
{
	global $db;
	$slots = array();
	$times = array();
	$days = array();
	$res = $db->query("SELECT Slot.*,Time.time,Day.name FROM Slot,Time,Day WHERE Slot.time_id=Time.id AND Slot.day_id=Day.id");
	while($row = mysql_fetch_array($res))
	{
		$slots[$row['time_id']][$row['day_id']]= $row['id'];
		$times[$row['time_id']] = $row['time'];
		$days[$row['day_id']] = $row['name'];
	}

	$text = '';

	$text .= '<table border="2" cellpadding="2" width="100%">';
	$text .= '<tr>';
	$text .= '<td  width="39px">&nbsp;</td>';

	for($i=1;$i<=sizeof($days);$i++)
	$text .=  '<td bgcolor="#cccccc" align="center">' . $days[$i] . '</td>';

	$text .= '</tr>';

	for($i=1;$i<=6;$i++)
	{
		$text .= '<tr>';
		$text .= '<td bgcolor="#cccccc"  width="39px" align="right">' . $times[$i] . '</td>';
		for($j=1;$j<=5;$j++)
		{
			$slot_id = $slots[$i][$j];
			$text .= $PrintCellFunction($slot_id,$cells[$slot_id]);
		}
		$text .= '</tr>';
	}
	$text .= '</table>';

	$text .= '<br/>';
	$text .= '<br/>';

	return $text;


}
?>
