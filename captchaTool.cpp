#include <iostream>
    using std::cout;
    using std::endl;
    using std::cin;
    
#include <fstream>
    using std::ifstream;
    using std::ofstream;

#include <string>
    using std::string;

#include <vector>
    using std::vector;
#include <curl/curl.h>

void makeCaptcha(string &outString, string word);
static size_t WriteCallback(void *contents, size_t size, size_t nmemb, void *userp);

int main()
{
    char website[100];
    cout << "Enter the website to fetch HTML code from: \n";
    cin.get(website, sizeof(website)); //get website read from user
    //cout << "website read: " << endl;
    
    CURL *curl;
    CURLcode res;
    string readBuffer; //string we will store the websites html code in
    string outString; //string we will write back to the file
    
    curl = curl_easy_init();
    if(curl)
    {
        //set option for using a url
        curl_easy_setopt(curl, CURLOPT_URL, website);
        //set option that we will send what we read to a function
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteCallback);
        //set option that what we read will be written into readbuffer
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, &readBuffer);
        //execute fetching the code
        res = curl_easy_perform(curl);
        //always cleanup
        curl_easy_cleanup(curl);
    }
    //cout << readBuffer << endl;
    
    ofstream outfile;
    outfile.open("indexTest.html");
    outfile << readBuffer;
    outfile.close();
    
    ifstream inFile; //file to read from
    inFile.open("indexTest.html");
    
    //check to see if file opened
    if (!inFile)
    {
        cout << "Error opening file.  Exiting...\n";
    }
    else
    {
        string word; //string to read into 
        inFile >> word;//attempt to read a word from the file
        while (!inFile.eof()) //check to see if end of file was reached
        {
            //cout << word << endl;
            
            //check to see if the word is in the sensitive list
            //better way of doing so when list gets large?
            if (word == "bsxyj" || word == "bomb" || word == "suicide"
                || word == "nuclear" || word == "prayer" || word == "religion")
            {
                makeCaptcha(outString, word);
            }
            else
            {
                //since the word wasn't sesitive, simply put it back
                //in its place to be written back to the file
                //" " is appended since we skip reading whitespaces
                outString.append(word);
                outString.append(" ");
            }
            inFile >> word; //attempt to get another word
        }
    }
    inFile.close(); //close the file we read from
    
    //cout << outString << endl;
    
    //reopen the file, this time to override what we just read so that
    //we can write the captcha words that we created to the file
    ofstream outFile;
    outFile.open("indexTest.html");
    outFile << outString;
    outFile.close();
    
    return 0;    
}

//Take a string, break it down character by character, and get the captcha
//letters corresponding to each character.  The html img code strings are  
//appended to outString, which is the string we will write back to the original file
void makeCaptcha(string &outString, string word)
{
    //make the string into a vector so we can parse each letter
    vector<char> v(word.begin(), word.end());
    
    //for each letter in the vector, write the image code in the HTML file
    for (int i = 0; i < v.size(); i++)
    {
        //cout << v[i] << endl; 
        outString.append( "<img src = \"" );
        outString.push_back(v[i]);
        outString.append(".png\">\n"); //resulting string is of format <img src = "j.png">
    }
}

//writes the contents of what was read from the website to a string
static size_t WriteCallback(void *contents, size_t size, size_t nmemb, void *userp)
{
    ((string*)userp)->append((char*)contents, size * nmemb);
    return size * nmemb;
}