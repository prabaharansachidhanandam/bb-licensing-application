// JavaScript Document

// local variable definitions used for finding the API
var apiHandle = null;
var findAPITries = 0;
var noAPIFound = "false";

// local variable used to keep from calling Terminate() more than once
var terminated = "false";

// local variable used by the content developer to debug
// This should be set to true during development to find errors.  However,
// This should be set to false prior to deployment.
var _debug = false;


/*******************************************************************************
**
** Returns the handle to API object if it was previously set, otherwise it
** returns null
**
** Inputs:  None
**
** Return:  Object - The value contained by the apiHandle variable.
**
*******************************************************************************/
function getAPIHandle()
{
   if ( apiHandle == null )
   {
      if ( noAPIFound == "false" )
      {
         apiHandle = getAPI();
      }
   }

   return apiHandle;
}


/*******************************************************************************
**
** This function looks for an object named API, first in the current window's
** frame hierarchy and then, if necessary, in the current window's opener window
** hierarchy (if there is an opener window).
**
** Inputs:  none
**
** Return:  Object - If the API object is found, it's returned, otherwise null
**                   is returned
**
*******************************************************************************/
function getAPI()
{
   var theAPI = findAPI( window );

   if ( (theAPI == null) &&
        (window.opener != null) &&
        (typeof(window.opener) != "undefined") )
   {
      theAPI = findAPI( window.opener );
   }

   if (theAPI == null)
   {
      //alert( "Unable to locate the LMS's API Implementation.\n" +
             //"Communication with the LMS will not occur." );

      noAPIFound = "true";
   }

   return theAPI
}


/*******************************************************************************
**
** This function looks for an object named API in parent and opener windows
**
** Inputs:  Object - The Window Object
**
** Return:  Object - If the API object is found, it's returned, otherwise null
**          is returned
**
*******************************************************************************/
function findAPI( win )
{
   while ( (win.API_1484_11 == null) &&
           (win.parent != null) &&
           (win.parent != win) )
   {
      findAPITries++;

      if ( findAPITries > 500 )
      {
         alert( "Error finding API -- too deeply nested." );
         return null;
      }

      win = win.parent;
   }

   return win.API_1484_11;
}


/*******************************************************************************
**
** This function requests the error code for the current error state from the
** LMS.
**
** Inputs:  None
**
** Return:  String - The last error code.
**
*******************************************************************************/
function retrieveLastErrorCode()
{
   // It is permitted to call GetLastError() after Terminate()

   var api = getAPIHandle();

   if ( api == null )
   {
      return "";
   }
   else
   {
      return api.GetLastError();
   }
}

/*******************************************************************************
**
** This function requests a textual description of the current error state from
** the LMS
**
** Inputs:  String - The error code.
**
** Return:  String - Textual description of the given error state.
**
*******************************************************************************/
function retrieveErrorInfo( errCode )
{
   // It is permitted to call GetLastError() after Terminate()

   var api = getAPIHandle();

   if ( api == null )
   {
      return "";
   }
   else
   {

      return api.GetErrorString( errCode );
   }
}

/*******************************************************************************
**
** Display the last error code, error description and diagnostic information.
**
** Inputs:  String - The error code
**
** Return:  None
**
*******************************************************************************/
function displayErrorInfo( errCode )
{
   if ( _debug )
   {
      var errString = retrieveErrorInfo( errCode );
      var errDiagnostic = retrieveDiagnosticInfo( errCode );
	
      alert( "ERROR: " + errCode + " - " + errString + "\n" +
             "DIAGNOSTIC: " + errDiagnostic );
   }
}

/*******************************************************************************
**
** This function requests additional diagnostic information about the given
** error code.  This information is LMS specific, but can help a developer find
** errors in the SCO.
**
** Inputs:  String - The error code.
**
** Return:  String - Additional diagnostic information about the given error
**                   code
**
*******************************************************************************/
function retrieveDiagnosticInfo( error )
{
   // It is permitted to call GetLastError() after Terminate()

   var api = getAPIHandle();

   if ( api == null )
   {
      return "";
   }
   else
   {
      return api.GetDiagnostic( error );
   }
}