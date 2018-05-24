Uses CURL library to download html code of a given website.
Stores website's html in a file, the file is read word by word searching for sensitive words.
The sensitive words are replaced, character by character, with CAPTCHA letters

CURL can be installed through CygWin setup
Compiled with g++ in CygWin

compile command: g++ captchaTool.cpp -lcurl
