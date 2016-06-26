<?php
namespace MakePdf\XPDF;
//Description
//This extension lets you display several paragraphs inside a frame. The use of tags allows to change the font, the style (bold, italic, underline), the size, and the color of characters.
//
//To do this, 2 methods are available:
//
//- The first one to define your tags:
//
//SetStyle(string tag, string family, string style, int size, string color [, int indent])
//
//tag: name of the tag
//family: family of the font
//style: N (normal) or combination of B, I, U
//size: size
//color: color (comma-separated RGB components)
//indent: to be specified for the paragraph tag; indents the first line by the indicated value
//
//It is possible to use empty strings or null values, except for the paragraph tag. The values are obtained by inheritance; for instance, with <p><u>, the non-specified values of <u> are replaced by those from <p>.
//
//- The second one to output text:
//
//WriteTag(float w, float h, string txt [, int border [, string align [, int fill [, mixed padding]]]])
//
//w: width of the line (0 to go from one margin to the other)
//h: height of a line
//txt: text to display - must have at least one tag at beginning and end to delimit a paragraph
//border: 0 for none, 1 to have a border (default value: 0)
//align: justification of text: L, R, C or J (default value: J)
//fill: 0 for none, 1 for background filling (default value: 0)
//padding: either a numerical value or a string of the form "left,top,bottom,right" with 2, 3 or 4 specified values (default value: 0)



/*

class FPDF
|
|_____________
|
|
class FPDF_TPL extends FPDF
|
|_____________
|
|
class FPDI extends FPDF_TPL
|
|_____________
|
|
class XPDF extends FPDI

*/

class XPDF extends FPDI
{
	// ################################# Initialization

	var $wLine; // Maximum width of the line
	var $hLine; // Height of the line
	var $Text; // Text to display
	var $border;
	var $align; // Justification of the text
	var $fill;
	var $Padding;
	var $lPadding;
	var $tPadding;
	var $bPadding;
	var $rPadding;
	var $TagStyle; // Style for each tag
	var $Indent;
	var $Space; // Minimum space between words
	var $PileStyle;
	var $Line2Print; // Line to display
	var $NextLineBegin; // Buffer between lines
	var $TagName;
	var $Delta; // Maximum width minus width
	var $StringLength;
	var $LineLength;
	var $wTextLine; // Width minus paddings
	var $nbSpace; // Number of spaces in the line
	var $Xini; // Initial position
	var $href; // Current URL
	var $TagHref; // URL for a cell

	// ################################# Public Functions

	function WriteTag($w,$h,$txt,$border=0,$align="J",$fill=0,$padding=0)
	{
		$this->wLine=$w;
		$this->hLine=$h;
		$this->Text=utf8_decode(trim($txt)); //$txt = utf8_decode($txt); //28.9.09 CSG
		$this->Text=preg_replace('/\n|\r|\t/','',$this->Text);
		$this->border=$border;
		$this->align=$align;
		$this->fill=$fill;
		$this->Padding=$padding;

		$this->Xini=$this->GetX();
		$this->href="";
		$this->PileStyle=array();
		$this->TagHref=array();
		$this->LastLine=false;

		$this->SetSpace();
		$this->Padding();
		$this->LineLength();
		$this->BorderTop();

		while($this->Text!="")
		{
			$this->MakeLine();
			$this->PrintLine();
		}

		$this->BorderBottom();
	}


	function SetStyle($tag,$family,$style,$size,$color,$indent=-1)
	{
		$tag=trim($tag);
		$this->TagStyle[$tag]['family']=trim($family);
		$this->TagStyle[$tag]['style']=trim($style);
		$this->TagStyle[$tag]['size']=trim($size);
		$this->TagStyle[$tag]['color']=trim($color);
		$this->TagStyle[$tag]['indent']=$indent;
	}


	// ############################ Private Functions

	function SetSpace() // Minimal space between words
	{
		$tag=$this->Parser($this->Text);
		$this->FindStyle($tag[2],0);
		$this->DoStyle(0);
		$this->Space=$this->GetStringWidth(" ");
	}


	function Padding()
	{
		if(preg_match('/^.+,/',$this->Padding)) {
			$tab=explode(",",$this->Padding);
			$this->lPadding=$tab[0];
			$this->tPadding=$tab[1];
			if(isset($tab[2]))
			$this->bPadding=$tab[2];
			else
			$this->bPadding=$this->tPadding;
			if(isset($tab[3]))
			$this->rPadding=$tab[3];
			else
			$this->rPadding=$this->lPadding;
		}
		else
		{
			$this->lPadding=$this->Padding;
			$this->tPadding=$this->Padding;
			$this->bPadding=$this->Padding;
			$this->rPadding=$this->Padding;
		}
		if($this->tPadding<$this->LineWidth)
		$this->tPadding=$this->LineWidth;
	}


	function LineLength()
	{
		if($this->wLine==0)
		$this->wLine=$this->fw - $this->Xini - $this->rMargin;

		$this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
	}


	function BorderTop()
	{
		$border=0;
		if($this->border==1)
		$border="TLR";
		$this->Cell($this->wLine,$this->tPadding,"",$border,0,'C',$this->fill);
		$y=$this->GetY()+$this->tPadding;
		$this->SetXY($this->Xini,$y);
	}


	function BorderBottom()
	{
		$border=0;
		if($this->border==1)
		$border="BLR";
		$this->Cell($this->wLine,$this->bPadding,"",$border,0,'C',$this->fill);
	}


	function DoStyle($tag) // Applies a style
	{
		$tag=trim($tag);
		$this->SetFont($this->TagStyle[$tag]['family'],
		$this->TagStyle[$tag]['style'],
		$this->TagStyle[$tag]['size']);

		$tab=explode(",",$this->TagStyle[$tag]['color']);
		if(count($tab)==1)
		$this->SetTextColor($tab[0]);
		else
		$this->SetTextColor($tab[0],$tab[1],$tab[2]);
	}


	function FindStyle($tag,$ind) // Inheritance from parent elements
	{
		$tag=trim($tag);

		// Family
		if($this->TagStyle[$tag]['family']!="")
		$family=$this->TagStyle[$tag]['family'];
		else
		{
			reset($this->PileStyle);
			while(list($k,$val)=each($this->PileStyle))
			{
				$val=trim($val);
				if($this->TagStyle[$val]['family']!="") {
					$family=$this->TagStyle[$val]['family'];
					break;
				}
			}
		}

		// Style
		$style1=strtoupper($this->TagStyle[$tag]['style']);
		if($style1=="N")
		$style="";
		else
		{
			reset($this->PileStyle);
			while(list($k,$val)=each($this->PileStyle))
			{
				$val=trim($val);
				$style1=strtoupper($this->TagStyle[$val]['style']);
				if($style1=="N")
				break;
				else
				{
					if(preg_match('/B/',$style1))
					$style['b']="B";
					if(preg_match('/I/',$style1))
					$style['i']="I";
					if(preg_match('/U/',$style1))
					$style['u']="U";
				}
			}
			$style=$style['b'].$style['i'].$style['u'];
		}

		// Size
		if($this->TagStyle[$tag]['size']!=0)
		$size=$this->TagStyle[$tag]['size'];
		else
		{
			reset($this->PileStyle);
			while(list($k,$val)=each($this->PileStyle))
			{
				$val=trim($val);
				if($this->TagStyle[$val]['size']!=0) {
					$size=$this->TagStyle[$val]['size'];
					break;
				}
			}
		}

		// Color
		if($this->TagStyle[$tag]['color']!="")
		$color=$this->TagStyle[$tag]['color'];
		else
		{
			reset($this->PileStyle);
			while(list($k,$val)=each($this->PileStyle))
			{
				$val=trim($val);
				if($this->TagStyle[$val]['color']!="") {
					$color=$this->TagStyle[$val]['color'];
					break;
				}
			}
		}

		// Result
		$this->TagStyle[$ind]['family']=$family;
		$this->TagStyle[$ind]['style']=$style;
		$this->TagStyle[$ind]['size']=$size;
		$this->TagStyle[$ind]['color']=$color;
		$this->TagStyle[$ind]['indent']=$this->TagStyle[$tag]['indent'];
	}


	function Parser($text)
	{
		$tab=array();
		// Closing tag
		if(preg_match('/^(<\/([^>]+)>).*/',$text,$regs)) {
			$tab[1]="c";
			$tab[2]=trim($regs[2]);
		}
		// Opening tag
		else if(preg_match('/^(<([^>]+)>).*/',$text,$regs)) {
			$regs[2]=preg_replace('/^a/',"a ",$regs[2]);
			$tab[1]="o";
			$tab[2]=trim($regs[2]);

			// Presence of attributes
			if(preg_match('/(.+) (.+)=\'(.+)\' */',$regs[2])) {
				$tab1=split(" +",$regs[2]);
				$tab[2]=trim($tab1[0]);
				while(list($i,$couple)=each($tab1))
				{
					if($i>0) {
						$tab2=explode("=",$couple);
						$tab2[0]=trim($tab2[0]);
						$tab2[1]=trim($tab2[1]);
						$end=strlen($tab2[1])-2;
						$tab[$tab2[0]]=substr($tab2[1],1,$end);
					}
				}
			}
		}
		// Space
		else if(preg_match('/^( ).*/',$text,$regs)) {
			$tab[1]="s";
			$tab[2]=$regs[1];
		}
		// Text
		else if(preg_match('/^([^< ]+).*/',$text,$regs)) {
			$tab[1]="t";
			$tab[2]=trim($regs[1]);
		}
		// Pruning
		$begin=strlen($regs[1]);
		$end=strlen($text);
		$text=substr($text, $begin, $end);
		$tab[0]=$text;

		$debug = Array(
        		"tab" => $tab, 
        		"space" => $this->Space, 
        		"delta" => $this->Delta,
        		"tagname" => $this->TagName,
        		"stringlength" => $this->StringLength,
        		"linelength" => $this->LineLength,
        		"line2print" => $this->Line2Print,
        		"nbspace" => $this->nbSpace,
		//"tagstyle" => $this->TagStyle,
        		"xini" => $this->Xini,
		);
		//t3lib_div::devLog('Debug Parser','csg_reiseangebote', 0, $debug);
		return $tab;
	}


	function MakeLine() // Makes a line
	{
		$this->Text.=" ";
		$this->LineLength=array();
		$this->TagHref=array();
		$Length=0;
		$this->nbSpace=0;

		$i=$this->BeginLine();

		$this->TagName=array();

		if($i==0) {
			$Length=$this->StringLength[0];
			$this->TagName[0]=1;
			$this->TagHref[0]=$this->href;
		}

		while($Length<$this->wTextLine)
		{
			$tab=$this->Parser($this->Text);
			$this->Text=$tab[0];
			if($this->Text==""  || $this->Text===NULL ) { //added  || $this->Text===NULL ?
				$this->LastLine=true;
				break;
			}

			if($tab[1]=="o") {
				array_unshift($this->PileStyle,$tab[2]);
				$this->FindStyle($this->PileStyle[0],$i+1);

				$this->DoStyle($i+1);
				$this->TagName[$i+1]=1;
				if($this->TagStyle[$tab[2]]['indent']!=-1) {
					$Length+=$this->TagStyle[$tab[2]]['indent'];
					$this->Indent=$this->TagStyle[$tab[2]]['indent'];
				}
				if($tab[2]=="a")
				$this->href=$tab['href'];
			}

			if($tab[1]=="c") {
				array_shift($this->PileStyle);
				$this->FindStyle($this->PileStyle[0],$i+1);
				$this->DoStyle($i+1);
				$this->TagName[$i+1]=1;
				if($this->TagStyle[$tab[2]]['indent']!=-1) {
					$this->LastLine=true;
					$this->Text=trim($this->Text);
					break;
				}
				if($tab[2]=="a")
				$this->href="";
			}

			if($tab[1]=="s") {
				$i++;
				$Length+=$this->Space;
				$this->Line2Print[$i]="";
				if($this->href!="")
				$this->TagHref[$i]=$this->href;
			}

			if($tab[1]=="t") {
				$i++;
				$this->StringLength[$i]=$this->GetStringWidth($tab[2]);
				$Length+=$this->StringLength[$i];
				$this->LineLength[$i]=$Length;
				$this->Line2Print[$i]=$tab[2];
				if($this->href!="")
				$this->TagHref[$i]=$this->href;
			}

		}

		trim($this->Text);
		if($Length>$this->wTextLine || $this->LastLine==true)
		$this->EndLine();
	}


	function BeginLine()
	{
		$this->Line2Print=array();
		$this->StringLength=array();

		$this->FindStyle($this->PileStyle[0],0);
		$this->DoStyle(0);

		if(count($this->NextLineBegin)>0) {
			$this->Line2Print[0]=$this->NextLineBegin['text'];
			$this->StringLength[0]=$this->NextLineBegin['length'];
			$this->NextLineBegin=array();
			$i=0;
		}
		else {
			//var_dump($this);
			preg_match('/^(( *(<([^>]+)>)* *)*)(.*)/',$this->Text,$regs);
			$regs[1]=preg_replace('/ /', "", $regs[1]);
			$this->Text=$regs[1].$regs[5];
			$i=-1;
		}

		return $i;
	}


	function EndLine()
	{
		if(end($this->Line2Print)!="" && $this->LastLine==false) {
			$this->NextLineBegin['text']=array_pop($this->Line2Print);
			$this->NextLineBegin['length']=end($this->StringLength);
			array_pop($this->LineLength);
		}

		while(end($this->Line2Print)=="" && $retpop!==NULL){ //modified
			$retpop=array_pop($this->Line2Print);
		}

		$this->Delta=$this->wTextLine-end($this->LineLength);

		$this->nbSpace=0;
		for($i=0; $i<count($this->Line2Print); $i++) {
			if($this->Line2Print[$i]==="")
			$this->nbSpace++;
		}
	}


	function PrintLine()
	{
		$border=0;
		if($this->border==1)
		$border="LR";
		$this->Cell($this->wLine,$this->hLine,"",$border,0,'C',$this->fill);
		$y=$this->GetY();
		$this->SetXY($this->Xini+$this->lPadding,$y);

		if($this->Indent!=-1) {
			if($this->Indent!=0)
			$this->Cell($this->Indent,$this->hLine,"",0,0,'C',0);
			$this->Indent=-1;
		}

		$space=$this->LineAlign();
		$this->DoStyle(0);

		$debug = Array(
        		"tagname" => $this->TagName, 
        		"line2print" => $this->Line2Print, 
        		"stringlength" => $this->StringLength,
		);
		//t3lib_div::devLog('Debug PrintLine','csg_reiseangebote', 2, $debug);


		for($i=0; $i<count($this->Line2Print); $i++)
		{
			if($this->TagName[$i]==1)
			$this->DoStyle($i);
			if($this->Line2Print[$i]==""){
			  $this->Cell($space,$this->hLine,"         ",0,0,'C',0,$this->TagHref[$i]);
			}else{
			  $this->Cell($this->StringLength[$i],$this->hLine,$this->Line2Print[$i],0,0,'C',0,$this->TagHref[$i]);
			}
		}

		$this->LineBreak();
		if($this->LastLine && $this->Text!="")
		$this->EndParagraph();
		$this->LastLine=false;
	}


	function LineAlign()
	{
		$space=$this->Space;
		if($this->align=="J") {
			if($this->nbSpace!=0)
			$space=$this->Space + ($this->Delta/$this->nbSpace);
			if($this->LastLine)
			$space=$this->Space;
		}

		if($this->align=="R")
		$this->Cell($this->Delta,$this->hLine,"",0,0,'C',0);

		if($this->align=="C")
		$this->Cell($this->Delta/2,$this->hLine,"",0,0,'C',0);

		return $space;
	}


	function LineBreak()
	{
		$x=$this->Xini;
		$y=$this->GetY()+$this->hLine;
		$this->SetXY($x,$y);
	}


	function EndParagraph() // Interline between paragraphs
	{
		$border=0;
		if($this->border==1)
		$border="LR";
		$this->Cell($this->wLine,$this->hLine/2,"",$border,0,'C',$this->fill);
		$x=$this->Xini;
		$y=$this->GetY()+$this->hLine/2;
		$this->SetXY($x,$y);
	}
} // End of class
?>