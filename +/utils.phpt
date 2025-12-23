<?


# shell()
$_SERVER['DOCUMENT_ROOT'] === shell('pwd')


# datetime()
date('Y-m-d H:i:s') === datetime()
datetime(1)
10 === strlen(datetime(1, false))


# num()
'' === num()
'0' === num(0)
'1' === num(1)
'2' === num(2)
'-1' === num(-1)
'0' === num(0.1)
'1,000,000' === num(1000000)
'1,000' === num(1000.1234)
'1,000.12' === num(1000.1234, 2)
'1,000.13' === num(1000.1299, 2)
'1,000.1234' === num(1000.1234, 4)
'1,000.123400' === num(1000.1234, 6)
'-1,000.123400' === num(-1000.1234, 6)


# text_to_title()
'' === text_to_title('')
'Abc Def 1GHI' === text_to_title('abc_def/1GHI')
