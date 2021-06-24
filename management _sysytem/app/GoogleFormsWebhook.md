[go back to readme.md](./readme.md)
# Google Forms Webhook Setup
 - We are using QuestionnaireSubmissionController's `store` method to store the responses given by candidates
 - Route is `/api/v1/questionnaire_submissions/{questionnaire_key}` with `post` method
 - `email`, `data` fields are required fields. 

## Webhook Setup
1. Create a google form
   - From settings menu (⚙ icon) make sure to enable `Collect emails`
2. Click on `Three dots` located at Top right corner  -> Click `Script Editor`
3. On left panel -> click `editor`  ( `< >`  icon) 
4. In `Code.gs` file put the following code (make neccessary changes)
    ```javascript
        function myFunction(e) {
          var API_URL = "{host}/api/v1/questionnaire_submissions/{questionnaire_key}"; //change as per need 
          var form = e.source;
          var responder_email = e.response.getRespondentEmail();
          var items = e.response.getItemResponses();
          var form_response = [];
          for( x of items){
            form_response.push({"question":x.getItem().getTitle(),"answer":x.getResponse()});
          }
          
          form_payload = {
              "email":responder_email,
              "data":form_response,
          };

          //setting headers
          var options = {
            method : "post",
            contentType : "application/json",
            headers :  {
              "Accept" : "application/json"
            },
            payload : JSON.stringify(form_payload)
          };
          //API call
          UrlFetchApp.fetch(API_URL, options);
        }
    ```
  
5. Now from left panel -> click  `Triggers`
    - `Add trigger` at bottom right
    - `Select event type` -> `On form submit`.
    - save it. 
    - if asked for permission by "PROJECT NAME" then aprove it.

    ....done....