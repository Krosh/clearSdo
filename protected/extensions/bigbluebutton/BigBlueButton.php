<?php
/**
 * BigBlueButton conferencing system API wrapper.
 * Tested with BigBlueButton 0.71a (last stable version), but should work with newer (currently beta) versions.
 * 
 * @see http://www.bigbluebutton.org/
 * @see http://code.google.com/p/bigbluebutton/wiki/API
 * 
 * @author Sergeyev Anton <me.is.an.astronaut@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version 1.0
 */

class BigBlueButton extends CApplicationComponent
{
    /**
     * @var string BigBlueButton server url
     */
	public $url;
	
	/**
	 * @var string path to API
	 */
	public $path="/bigbluebutton/api/";
	
	/**
	 * @var int BigBlueButton server port
	 */	
	public $port=80;
	
	/**
	 * @var string security salt
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Usage
	 */
	public $salt;
	
	/**
	 * @var string common attendee password (you may also set unique passwords for each conference) 
	 */
	public $attendeePW;
	
	/**
	 * @var string common moderator password (you may also set unique passwords for each conference)
	 */
	public $moderatorPW;
	
	/**
	 * @var string common logout url, 
	 * which will be used to redirect user when he leaves the conference.
	 * You may also set unique logout url for each conference.
	 * If not set, site root will be used by default.
	 */
	public $logoutUrl="/";
		
	//conference roles used in BigBlueButton API
	const ROLE_VIEWER="VIEWER";
	const ROLE_MODERATOR="MODERATOR";	
	
	//response codes used in BigBlueButton API
	const API_QUERY_FAILED="FAILED";
	const API_QUERY_SUCCESS="SUCCESS";
	
	const ERROR_WRONG_ANSWER=1;
	const ERROR_QUERY_FAILED=2;
	const ERROR_WRONG_PARAMETERS=3;
	
	const MEETING_STATE_ANY=1;
	const MEETING_STATE_RUNNING=2;
	const MEETING_STATE_COMPLETED=3;
	
	public function init()
	{
	    Yii::import('ext.bigbluebutton.BigBlueButtonException');
	    $this->logoutUrl=Yii::app()->createAbsoluteUrl($this->logoutUrl);
	    parent::init();		
	}
	
	/**
	 * Constructs API query url to create new meeting with given name and other optional parameters.
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Create_Meeting
	 * @param string $name meeting name.
	 * @param array $params optional parameters: meetingID (by default generated automatically),
	 * attendeePW & moderatorPW, logoutURL (by default common values from configuration will be used)
	 * and/or other parameters described in API docs.
	 * @return string url - do not send it to user directly, it will not redirect him 
	 * to new conference (use joinMeeting instead)
	 */
	public function getCreateMeetingUrl($name, $params=array())
	{		    		
	    $params['name']=$name;
	    
	    if (!isset($params['meetingID']))
	        $params['meetingID']=time().rand(1, 1000);
	    
	    if (!isset($params['attendeePW']))
	        $params['attendeePW']=$this->attendeePW;
	    
	    if (!isset($params['moderatorPW']))
	        $params['moderatorPW']=$this->moderatorPW;
	    
		if (!isset($params['logoutURL']))
			$params['logoutURL']=$this->logoutUrl;

        if (!isset($params['record']))
            $params['record']="true";

		$url=$this->createApiQuery(
	        "create", 
	        $params
		);
		
		return $url;
	}
	
	/**
	 * Creates new meeting with given name and optional parameters.
	 * @see BigBlueButton::getCreateMeetingUrl()
	 * @param string $name meeting name
	 * @param array $params optional parameters (passwords, logout url, etc)
	 * @return array BigBlueButton's API response
	 */
	public function createMeeting($name, $params=array())
	{
	    $url=$this->getCreateMeetingUrl($name, $params);
	    $data=$this->getApiResponse($url);	 
	    return $data;
	}
	
	/**
	 * Constructs url for specific user to join running conference.
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Join_Meeting	 
	 * @param string $meetingId existing meeting id 
	 * (you may find it in the result of $this->createMeeting() method if you didn't set it yourself).
	 * @param string $username 
	 * @param string $userId or null to use Yii::app()->user->id 
	 * @param int $role BigBlueButton::ROLE_VIEWER or BigBlueButton::ROLE_MODERATOR
	 * @param array $params optional parameters described in API docs; should include
	 * viewer or moderator password if you're not using common passwords.
	 * @return string meeting url that you can send to user or redirect him yourself
	 */
	public function getJoinMeetingUrl($meetingId, $username, $userId=null, $role=self::ROLE_VIEWER, $params=array())
	{
	    $params['fullName']=$username;	    
	    $params['meetingID']=$meetingId;
	    $params['role']=$role;
	    	    
	    if ($userId==null && isset(Yii::app()->user) && isset(Yii::app()->user->id))
	        $params['userID']=Yii::app()->user->id;
	    else
	        $params['userID']=$userId;
	    
	    if (!isset($params['password']))
	    {
	        if ($role==self::ROLE_VIEWER)
	            $params['password']=$this->attendeePW;
	        else
	            $params['password']=$this->moderatorPW;
	    } 
	    
	    $url=$this->createApiQuery(
	            "join",
	            $params
	    );
	    
	    return $url;
	}
	
	/**
	 * Constructs API query to end specified meeting.
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#End_Meeting
	 * @see BigBlueButton::endMeeting()
	 * @param string $meetingId
	 * @param string $password moderator password for conference or null if you use common passwords
	 * @return string url which you can send to user or request it yourself internally
	 */
	public function getEndMeetingUrl($meetingId, $password=null)
	{
	    $url=$this->createApiQuery("end", array(
	            "meetingID"=>$meetingId,
	            "password"=>$password==null?$this->moderatorPW:$password,
	    ));	    
	    return $url;
	}
	
	/**
	 * Ends meeting with given id.
	 * @see BigBlueButton::getEndMeetingUrl()
	 * @param string $meetingId
	 * @param string $password moderator password for conference or null if you use common passwords
	 * @return array BigBlueButton's API response
	 */
	public function endMeeting($meetingId, $password=null)
	{
	    $url=$this->getEndMeetingUrl(
	            $meetingId, 
	            $password==null?$this->moderatorPW:$password);
	    $data=$this->getApiResponse($url);
	    return $data;
	}

	/**
	 * Receives meetings depending on state (running, completed or any).
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Get_Meetings
	 * @param int $state BigBlueButton::MEETING_STATE_COMPLETED, 
	 * BigBlueButton::MEETING_STATE_ANY or BigBlueButton::MEETING_STATE_RUNNING, default is any
	 * @return array of meetings
	 */
	public function getMeetings($state=self::MEETING_STATE_ANY)
	{
	    $url=$this->createApiQuery("getMeetings");
	    $data=$this->getApiResponse($url);
	    return $this->processMeetings($data, $state, false, null, null, null);
	}
	
	/**
	 * Receives full meetings information depending on state (running, completed or any).
	 * Uses additional API query for each meeting (could be slow).
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Get_Meetings
	 * @param int $state BigBlueButton::MEETING_STATE_COMPLETED, 
	 * BigBlueButton::MEETING_STATE_ANY or BigBlueButton::MEETING_STATE_RUNNING, default is any
	 * @return array of meetings
	 */
	public function getFullMeetings($state=self::MEETING_STATE_ANY)
	{
	    $url=$this->createApiQuery("getMeetings");
	    $data=$this->getApiResponse($url);
	    return $this->processMeetings($data, $state, true, null, null, null);
	}
	
	/**
	 * Receives full meetings information with join/end urls for specified user 
	 * depending on meeting state (running, completed or any).
	 * Uses additional API query for each meeting (could be slow).
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Get_Meetings
	 * @param string $username
	 * @param string $userId default is Yii::app()->user->id. 
	 * @param int $state BigBlueButton::MEETING_STATE_COMPLETED,
	 * BigBlueButton::MEETING_STATE_ANY or BigBlueButton::MEETING_STATE_RUNNING, default is any.
	 * @param int $role BigBlueButton::ROLE_VIEWER or BigBlueButton::ROLE_MODERATOR, default is viewer.
	 * @return array of meetings
	 */
	public function getMeetingsForUser($username, $userId=null, $state=self::MEETING_STATE_ANY, $role=self::ROLE_VIEWER)
	{
	    $url=$this->createApiQuery("getMeetings");
	    $data=$this->getApiResponse($url);
	    if ($userId==null && isset(Yii::app()->user) && isset(Yii::app()->user->id))
	        $userId=Yii::app()->user->id;	    
	    return $this->processMeetings($data, $state, true, $userId, $username, $role);
	}
	
	/**
	 * Receives full meeting information
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Get_Meeting_Info
	 * @param string $id meeting id
	 * @param string $password moderator password for given meeting or null if you use common passwords
	 * @return array meeting info
	 */
	public function getMeeting($id, $password=null)
	{
	    $data=$this->getMeetingInfo($id, $password);
	    return $this->processMeetingData($data, null, null);
	}
	
	/**
	 * Receives full meeting information with join/end urls for specified user
	 * @see http://code.google.com/p/bigbluebutton/wiki/API#Get_Meeting_Info
	 * @param string $meetingId
	 * @param string $username
	 * @param string $userId, default is Yii::app()->user->id 
	 * @param int $role BigBlueButton::ROLE_VIEWER or BigBlueButton::ROLE_MODERATOR, default is viewer
	 * @param string $password moderator password for given meeting or null if you use common passwords
	 * @return array meeting info
	 */
	public function getMeetingForUser($meetingId, $username, $userId=null, $role=self::ROLE_VIEWER, $password=null)
	{
	    if ($userId==null && isset(Yii::app()->user) && isset(Yii::app()->user->id))
	        $userId=Yii::app()->user->id;
	    $data=$this->getMeetingInfo($meetingId, $password);
	    return $this->processMeetingData($data, $userId, $username, $role);
	}
	
	/**
	 * Checks whether specified meeting is runnning
	 * @param string $meetingId
	 * @return bool
	 */
	public function meetingIsRunning($meetingId)
	{
	    $url=$this->createApiQuery(
	            "isMeetingRunning",
	            array("meetingID"=>$meetingId));
	    $data=$this->getApiResponse($url);
	    return $data['running'];
	}
	
	/**
	 * Receives meeting info for given id
	 * @param int $id meeting id
	 * @param string $password moderator password for given meeting or null if you use commond passwords
	 * @return array raw API response
	 */
	protected function getMeetingInfo($id, $password=null)
	{
	    $url=$this->createApiQuery("getMeetingInfo", array(
	            "meetingID"=>$id,
	            "password"=>$password==null?$this->moderatorPW:$password,
	    ));
	
	    $data=$this->getApiResponse($url);
	    return $data;
	}
	
	/**
	 * Processes multiple meetings
	 * @see BigBlueButton::processMeeting()
	 * @param array $data meetings list
	 * @param int $state requested state to filter meetings
	 * @param bool $requestInfo 
	 * @param mixed $userId
	 * @param string $username
	 * @param int $role
	 */
	protected function processMeetings($data, $state, $requestInfo=true, $userId=null, $username=null, $role=null)
	{	    
	    $meetings=array();
	    
	    if (!isset($data['meetings']) ||
	        !isset($data['meetings']['meeting']))
	        return $meetings;
	    
	    //$data['meetings']['meeting'] may be array of meetings,
	    //or, if there's only one meeting, it will be array of meeting info itself
	    if (isset($data['meetings']['meeting']['meetingID']))
	    {
	        $result=$this->processMeeting(
	                $data['meetings']['meeting'], 
	                $state, $requestInfo, $userId, 
	                $username, $role
	        );
	        if (!$result==false)
	            $meetings[]=$result;	        
	    } 
	    else
	    {
	        //processing multiple meetings...
	        foreach ($data['meetings']['meeting'] as $meeting)
	        {	            	            
	            $result=$this->processMeeting(
	                    $meeting, $state, 
	                    $requestInfo, $userId, 
	                    $username, $role
	            );
	            if ($result===false)
	                continue;
	            $meetings[]=$result;	            	
	        }
	    }
	    return $meetings;
	} 
	
	/**
	* Processes raw meeting data, requests additional info if necessary
	* @param array $meeting raw meeting data from API's getMeetings method
	* @param int $state BigBlueButton::MEETING_STATE_RUNNING, BigBlueButton::MEETING_STATE_COMPLETED
	* or BigBlueButton::MEETING_STATE_ANY
	* @param bool $requestInfo if true, the additional meeting data will be requested in separate query
	* @param string $userId if userId, username and role are set, additional data for given user will be prepared
	* @param string $username
	* @param int $role
	* @return array meeting data or false if it's state is inappropriate
	*/
	protected function processMeeting($meeting, $state, $requestInfo, $userId=null, $username=null, $role)
	{	    
	    //filter meeting by state
	    if ((!$meeting['running'] && $state==self::MEETING_STATE_RUNNING) ||
	        ($meeting['running'] && $state==self::MEETING_STATE_COMPLETED))
	        return false;
	    //request and process additional meeting data
	    if ($requestInfo)
	    {	        
	        $meetingData=$this->getMeetingInfo($meeting['meetingID'], $meeting['moderatorPW']);
			$meeting=$this->processMeetingData($meetingData, $userId, $username, $role);
	    }	    
	    return $meeting;
	}
	
	/**
	 * Processes meeting attendees, user specific data and dates
	 * @param array $meetingData
	 * @param string $userId
	 * @param string $username
	 * @param int $role
	 * @return array $meetingData
	 */
	protected function processMeetingData($meetingData, $userId=null, $username=null, $role=null)
	{		    
	    $meetingData=$this->processMeetingAttendees($meetingData);
	    if ($userId!=null && !$meetingData['hasBeenForciblyEnded'])
	        $meetingData=$this->processUserMeeting($meetingData, $userId, $username, $role);
        //odd thing - i have no meeting names in responses,
        //though documentation says it should be there.
        //may be it's just a bug of version 0.71
        if (!isset($meetingData['meetingName']))
            $meetingData['meetingName']='';
        if (isset($meetingData['startTime']))
            $meetingData['startTime']=strtotime($meetingData['startTime']);
        if (isset($meetingData['endTime']))    
            $meetingData['endTime']=strtotime($meetingData['endTime']);
        return $meetingData;
	}
	
	/**
	 * Sets meeting join/end urls and flag indicating if supplied user is a member of given meeting
	 * @param array $meetingData
	 * @param string $userId
	 * @param string $username
	 * @param int $role BigBlueButton::ROLE_VIEWER or BigBlueButton::ROLE_MODERATOR 
	 * @return array $meetingData
	 */
	protected function processUserMeeting($meetingData, $userId, $username, $role)
	{	    
	    $joinUrl=false;
	    $endUrl=false;
	    $currentUserJoined=false;
	    $currentUserRole=false;
	    foreach ($meetingData["attendees"] as &$attendee)
	    {
	        if ($attendee['userID']==$userId)
	        {
	            $currentUserRole=$attendee['role'];
	            $currentUserJoined=true;	        
	            break;
	        }
	    }
	    //set moderator password and url which ends meeting if supplied role is moderator
	    //or user's id is similar to moderator's id
	    if ($role==self::ROLE_MODERATOR ||
	        ($currentUserJoined && $currentUserRole==self::ROLE_MODERATOR))
	    {
	        $pass=$meetingData['moderatorPW'];
	        //allow user end meeting if he's moderator
	        $endUrl=$this->getEndMeetingUrl(
	                $meetingData['meetingID'],
	                $pass
	        );
	    }
	    else
	        $pass=$meetingData['attendeePW'];
	    //if user with supplied id is not already joined to conference,
	    if (!$currentUserJoined)
	        $joinUrl=$this->getJoinMeetingUrl(
	                $meetingData['meetingID'],
	                $username,
	                $userId,
	                $role,
	                array("password"=>$pass)      
	        );
	    $meetingData["joinUrl"]=$joinUrl;
	    $meetingData["endUrl"]=$endUrl;
	    $meetingData["currentUserJoined"]=$currentUserJoined;
	    return $meetingData;
	}
	
	/**
	 * Extract attendees from meeting data
	 * @param array $meeting
	 * @return array processed meetings
	 */
	protected function processMeetingAttendees($meeting)
	{		    
	    $attendees=array();
	    if (!isset($meeting['attendees']) || !isset($meeting['attendees']['attendee']))
	    {
	        $meeting['attendees']=$attendees;
	        return $meeting;
	    }
	    //$meeting['attendees'] may contain array of attendees,
	    //or, if there's only one attendee, array with it's info.
	    if (isset($meeting['attendees']['attendee']['userID']))
	    {
	        $attendees[]=$meeting['attendees']['attendee'];
	    } else
	    {
	        $attendees=$meeting['attendees']['attendee'];
	    }
	    $meeting['attendees']=$attendees;
	    return $meeting;
	}
	
	/**
	 * Requests BigBlueButton API by given url
	 * @param string $url
	 * @return array BigBlueButton API response
	 */
	public function getApiResponse($url)
	{
		if ($this->url==null || $this->salt==null)
			throw new BigBlueButtonException(
				"Parameters supplied to extension must contain BigBlueButton server url and security salt",
				self::ERROR_WRONG_PARAMETERS
			);
		$data=$this->loadXml($url);
		if ($data['returncode']!=BigBlueButton::API_QUERY_SUCCESS)
		    throw new BigBlueButtonException(
		            "API query error ".
					$url.": ".$data['message'].
		            " \nError code: ".$data['messageKey'], 
		            BigBlueButton::ERROR_QUERY_FAILED
		    );
		return $data;
	}
	
	/**
	 * Loads xml response from API server by given url and converts it to php array
	 * @param string $url
	 * @return array response
	 */
	protected function loadXml($url)
	{				    
		libxml_use_internal_errors(1);		
		$data=@simplexml_load_file($url);		
		if (!$data)
		{
			$errorString="";
			foreach (libxml_get_errors() as $error)
			{
				$errorString.="\n".$error->message;
			}
			libxml_clear_errors();
		    throw new BigBlueButtonException(
		            "API query error: ".$errorString,
		            BigBlueButton::ERROR_WRONG_ANSWER
		    );			
		}
		$data=$this->xmlToArray($data);
		return $data;
	}
	
	/**
	 * Converts xml to array and strings to boolean/null values
	 * @param mixed $xml
	 * @return mixed $result
	 */
	private function xmlToArray($xml) {
	    if (is_object($xml) && get_class($xml) == 'SimpleXMLElement') {
	        $attributes = $xml->attributes();
	        foreach($attributes as $k=>$v) {	            
	            if ($v)
	                $a[$k]=$v;
	        }
	        $x = $xml;
	        $xml = get_object_vars($xml);
	    }
	    if (is_array($xml)) {
	        if (count($xml) == 0) return (string) $x;
	        foreach($xml as $key=>$value) {
	            $r[$key] = $this->xmlToArray($value);
	        }
	        if (isset($a)) $r['@attributes'] = $a;
	        return $r;
	    }
	    $xml=(string)$xml;
	    switch ($xml)
	    {
	        case "false":
	            $xml=false;
	            break;
	        case "true":
	            $xml=true;
	            break;
	        case "null":
	            $xml=null;
	            break;
	    }
	    return $xml;
	}

    public function getRecordings($meetingId)
    {
        $url=$this->createApiQuery(
            "getRecordings",
            array("meetingID"=>$meetingId));
        $data=$this->getApiResponse($url);
        return $data;
    }
	
	/**
	 * Creates API query based on given method and optional parameters.
	 * You can implement new functions support with this method, 
	 * which this class does not contain yet (e.g. records and other BigBlueButton 0.8beta features)
	 * @see http://code.google.com/p/bigbluebutton/wiki/API
	 * @param string $method
	 * @param array $params
	 * @throws BigBlueButtonException
	 * @return string url
	 */
	public function createApiQuery($method, $params=null)
	{
	    if (is_array($params))
	        $paramsStr=http_build_query($params);
	    else
	        $paramsStr="";
	    //secure query with hash containing all query parameters and salt
	    $sha=sha1($method.$paramsStr.$this->salt);
	    $url=$this->url.":".$this->port.$this->path.$method."?".$paramsStr."&checksum=".$sha;
	    return $url;
	}
	
	/**
	 * Checks if defined API host is available
	 * @return bool
	 */
	public function serverIsAvailable()
	{
	    //empty query will respond with server status
	    $url=$this->createApiQuery("");
	    try {
	        $data=$this->loadXml($url);
	        return $data['returncode']!=self::API_QUERY_FAILED;
	    } 
	    //may be host responded, but it's not BigBlueButton server,
	    //so the response format will be wrong, and we'll
	    //return false anyway
	    catch (BigBlueButtonException $ex)
	    {
	        return false;
	    }
	}
}