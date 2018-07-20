<?php

require_once 'constants.php';
$bookIDs = glob(UNICODE_SRC . '*', GLOB_ONLYDIR);
$classArrar = ['author', 'footnote-head', 'hide', 'index', 'italic', 'level1-title', 'level2-title', 'level3-title', 'level4-title', 'level5-title', 'lower-alpha', 'myquote', 'para-title', 'part-title', 'publisher', 'quote-author', 'ref', 'text-center', 'text-right', 'title', 'titleauthor', 'upper-alpha', 'verse', 'verse-author', 'verse-num', 'vertical-delimiter', 'en', 'maintext','level1', 'unnumbered', 'numbered', 'level2', 'level3', 'titlepage', 'footnote', 'num', 'sign', 'resp', 'letter' ,'uvacha', 'end', 'color', 'text' , 'addr'];
$classes = '';
$aside = '';
$comment = '';
$isbn = '';
$jrd = '';

foreach ($bookIDs as $bookID) {

	$bookID = preg_replace('/.*\/(.*)/', "$1", $bookID);

	$files = getAllFiles($bookID);
	$tempArray = [];
	foreach ($files as $file) {
		$lines = file($file);
		
		$file = preg_replace('/.*\/(.*)/', "$1", $file);
		foreach ($lines as $line) {

			$line = trim($line);
			
			$xhtmlFileContents = strip_tags($line);
			// new file normalizations
			$xhtmlFileContents = str_replace('.', '. ', $xhtmlFileContents);
			$xhtmlFileContents = preg_replace('/\s+/', ' ', $xhtmlFileContents);
			$xhtmlFileContents = preg_replace('/ /', "\n", $xhtmlFileContents);
			$xhtmlFileContents = str_replace('–', '-', $xhtmlFileContents);		
			
			$finalWords = explode("\n",$xhtmlFileContents);
			
			
			foreach($finalWords as $word){

				if(preg_match('/È|É|Ë|Ì|Ï|Ò|Ó|Õ|Ö|Ø|Œ|Ù|œ|Ú|Û|Ü|ß|â|μ|ä|å|æ|š|é|ë|%|&|ï|ñ|ò|ó|ô|‰|õ|ö|ù|û|ü|‹|Ÿ|›|ÿ|@|¢|£|¤|¥|©|ª|«|®|°|»|¿|À|Á|Â|Ã|Å|Æ|~|Ç|¢|£|¤|¥|¦|§|¨|©|ª|«|¬|®|¯|°|±|²|³|¶|¸|¹|º|»|¼|½|À|Á|Â|Ã|Ä|Å|Æ|Ç|É|Ë|ï|ó|û|ü/u', $word)){
					array_push($tempArray, $word);
				}
			}

			if(file_exists(RAW_SRC . $bookID . '/' . $bookID . ".junk.txt")) unlink(RAW_SRC . $bookID . '/' . $bookID . ".junk.txt");
		}
	}
	file_put_contents(RAW_SRC . $bookID . '/' . $bookID . ".junk.txt", implode("\n",$tempArray));
}

// file_put_contents('jrd.txt', $jrd);
// file_put_contents('aside.txt', $aside);
// file_put_contents('comment.txt', $comment);
// file_put_contents('comment.txt', $comment);
// file_put_contents('isbn.txt', $isbn);

function getAllFiles($bookID) {

	$allFiles = [];

	$folderPath = UNICODE_SRC . $bookID . '/';

	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));

	foreach($iterator as $file => $object) {

		if(preg_match('/.*' . $bookID . '\/\d+.*\.xhtml$/',$file)) array_push($allFiles, $file);
	}

	sort($allFiles);

	return $allFiles;
}
?>
