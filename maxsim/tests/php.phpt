<?

# PHP
phpversion()
7 <= explode('.', phpversion())[0]


# PHP var
true === true
true === true
false === false
null === null
'' === null #pass_fail
true === 1 #pass_fail
false === 0 #pass_fail
false === -1 #pass_fail
true === (1 != 1) #pass_fail
3 == round(3)
(float) 3 === round(3)
3 === round(3) #pass_fail


# Operators
true === (1 === 1)
false === (1 !== 1)
true === (1 == 1)
false === (1 != 1)
true === ('a' == 'a')
false === ('a' != 'a')
true === (12 > 11)
true === (rand(1,2) > 0)
false === (rand(1,2) <= 0)
true === (1 == 1 AND 2 == 2 AND (3 == 3 AND 4 == 4))
false === (1 == 1 AND 2 == 2 AND (3 == 3 AND 4 == 5))


# Arrays
array() === []
2 === count(['', ''])
array('abc','123') === ['abc', '123']
array('abc','123') === ['abc', '12'] #pass_fail
array(1 => 2) === [1 => 2]
array(1 => 2) === [0 => 2, 1 => 2] #pass_fail


# Integers
0 === 0
1 === 1
2 === 2
-1 === -1


# Strings
'abc' === 'abc'
'abc' === "abc"
'ábc' === 'ábc'
'abc' === 'ábc' #pass_fail
'123' === '123'
'abc 123 $%([{?' === 'abc 123 $%([{?'


# Numeric operators
10 < true #pass_fail
10 < null #pass_fail
10 < 0 #pass_fail
10 < 1 #pass_fail
10 < -1 #pass_fail
10 < 9.01 #pass_fail
10 < 10.01
1.01 < 0.90 #pass_fail
1.01 < 1.02
10 < -1 #pass_fail
10 < 'abc123'

10 < 9 #pass_fail
10 < 10 #pass_fail
10 < 11
10 < 99

10 <= 9 #pass_fail
10 <= 10
10 <= 11
10 <= 99
9.9 <= 9.91
9.9 <= "9.89" #pass_fail
9.9 <= '9.89' #pass_fail

10 > 9
10 > 10 #pass_fail
10 > 11 #pass_fail

10 >= 9
10 >= 10
10 >= 11 #pass_fail


# ms_limit
null === usleep( 110) #limit_ms=1
null === usleep(1100) #limit_ms=1 #pass_fail


# Simplest tests
true
false #pass_fail
null #pass_fail
0 #pass_fail
1
2
-1
'' #pass_fail
"" #pass_fail
'a'
"b"
[] #pass_fail
array() #pass_fail
[1]
strlen('abc')
strlen('') #pass_fail
0 + 0 #pass_fail
0 + 1
mt_rand(1,9)
