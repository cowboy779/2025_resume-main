package medios.cmm.cod.etc.lifecenter.service;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Base64;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Timer;
import java.util.TimerTask;

import javax.crypto.Cipher;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.fasterxml.jackson.core.JsonParseException;
import com.fasterxml.jackson.databind.JsonMappingException;
import com.fasterxml.jackson.databind.ObjectMapper;

import egovframework.rte.fdl.cmmn.EgovAbstractServiceImpl;
import medios.cmm.cod.etc.lifecenter.mapper.LifeCenterMapper;
import medios.cmmn.collection.MData;
import medios.cmmn.collection.MMultiData;
import medios.cmmn.common.ServiceInterface;
import medios.cmmn.exception.BizException;
import medios.cmmn.util.MMultiDataUtil;

@Service
public class LifeCenterService extends EgovAbstractServiceImpl implements ServiceInterface {

	static TimerTask task;

	@Autowired
	private LifeCenterMapper lifeCenterMapper;

	// 현재시간
	public static String getNow() {
		DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		Date date = new Date();

		return dateFormat.format(date);
	}

	// AES256 암호화
	public static byte[] IV = { 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
			0x00, 0x00 };

	public static String aesEncrypt(String encKey, byte[] iv, String paramInput) throws Exception {
		Cipher cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
		SecretKeySpec key = new SecretKeySpec(encKey.getBytes("UTF-8"), "AES");
		cipher.init(Cipher.ENCRYPT_MODE, key, new IvParameterSpec(iv));

		byte[] cipherText = cipher.doFinal(paramInput.getBytes("UTF-8"));

		return Base64.getEncoder().encodeToString(cipherText);
		// return "FPg9yiuiBmzYIixL1zL549mA6zzPo4rbRSvCSAc6e2eB7q9AjONroEuMJtB7kJ8u";
	}

	/**
	 * JsonObject를 Map<String, Object>으로 변환한다.
	 *
	 * @param jsonObj JSONObject.
	 * @return Map<String, Object>.
	 */
	@SuppressWarnings("unchecked")
	public static Map<String, Object> getMapFromJsonObject(JSONObject jsonObj) {
		Map<String, Object> map = null;

		try {
			map = new ObjectMapper().readValue(jsonObj.toJSONString(), Map.class);
		} catch (JsonParseException e) {
			throw new BizException("JsonParseException 오류 \n\n\n"+e);
		} catch (JsonMappingException e) {
			throw new BizException("JsonMappingException 오류 \n\n\n"+e);
		} catch (IOException e) {
			throw new BizException("IOException 오류 \n\n\n"+e);
		}
		return map;
	}
	
	/* 22.05.03 
	 * (현재사용중)
	 * 파람세팅 및 URL 데이터송수신 후 인서트 관련 분기점
	 * */
	@SuppressWarnings("unchecked")
	public MData sendRest(MData mParamDataList) throws Exception {
		String resourceType = mParamDataList.get("resourceType").toString();
		String patientNumber = mParamDataList.get("patientNumber").toString();
		String patientName = mParamDataList.get("patientName").toString();
		String birthDate = mParamDataList.get("birthDate").toString();
		String patientPhone = mParamDataList.get("patientPhone").toString();

		String startDate = mParamDataList.get("startDate").toString();
		String endDate = mParamDataList.get("endDate").toString();
		
		String managerId = mParamDataList.get("managerId").toString();
		String centerCode = mParamDataList.get("centerCode").toString();
		String careAgencyCode = mParamDataList.get("careAgencyCode").toString();
		String encKey = mParamDataList.get("encKey").toString();
		String sendURL = mParamDataList.get("sendURL").toString();
		//Long timer = Long.parseLong((String) mParamDataList.get("timer"));
		//int rstDiff = Integer.parseInt(mParamDataList.get("rstDiff").toString());

		String param = managerId + "|" + careAgencyCode + "|" + getNow();
		String token = aesEncrypt(encKey, IV, param); // 토큰 생성 및 암호화---
		
		JSONObject jObject = new JSONObject();

		jObject.put("resourceType", resourceType);
		jObject.put("patientNumber", patientNumber);// 62817236
		jObject.put("patientName", patientName);    // 홍길동
		jObject.put("birthDate", birthDate); 	    // 19930516
		jObject.put("patientPhone", patientPhone);  // 01033337777
		jObject.put("startDate", startDate); 	    // 20220101
		jObject.put("endDate", endDate); 		    // 20220131
		
		jObject.put("managerId", managerId);
		jObject.put("centerCode", centerCode);
		jObject.put("careAgencyCode", careAgencyCode);
		jObject.put("token", token);
		
		MData mResultData = new MData();
		String jsonValue = null;
		String result = null;
		int cnt = 0;
		
		jsonValue = jObject.toJSONString();
		
		result = sendREST(sendURL, jsonValue);
		
		cnt += semiInsertData(resourceType, result);
		
		if(cnt == -1) {
			mResultData.set((String) jObject.get("resourceType"), result);
		}else {
			mResultData.set((String) jObject.get("resourceType"), cnt);
		}
	
//		JSONParser jsonParse = new JSONParser();
//	    JSONArray jarr = new JSONArray();
//	    jarr = (JSONArray) jsonParse.parse(result);

//		for (int i = 0; i < jarr.size(); i++) {
//			mResultData.addRow(getMapFromJsonObject((JSONObject) jsonParse.parse(jarr.get(i).toString())));
//		}
	    
		/*
		Calendar cal = Calendar.getInstance();
		DateFormat df = new SimpleDateFormat("yyyyMMdd");

		for(int day=0; day<=rstDiff; day++) {
		    cal.setTime(df.parse(startDate));
		    cal.add(Calendar.DATE, day);
		       
		    jObject.replace("startDate", df.format(cal.getTime()));
		    jObject.replace("endDate", df.format(cal.getTime()));
		        
		    jsonValue = jObject.toJSONString();
		       
		    result = sendREST(sendURL, jsonValue); // 데이터 송수신---
		    
		    jarr.add((JSONArray) jsonParse.parse(result));
		}
		*/
		return mResultData;
	}
	
	/* 22.05.03 
	 * (현재사용중)
	 * HTTP URL 커넥션 전송 관련
	 * */
	public static String sendREST(String sendURL, String jsonValue) throws IllegalStateException {
		StringBuilder sb = new StringBuilder();
		String line;
		
		HttpURLConnection conn = null;
		OutputStream os = null;
		BufferedReader rd = null;
		
		List<String> sendList = new ArrayList<>();
		if(sendList.indexOf(sendURL) >= 0) throw new BizException("URL 에러");

		try {
			sendList.add(sendURL);
			// StringBuilder urlBuilder = new StringBuilder(sendURL);
			// URL url = new URL(urlBuilder.toString());
			URL url = new URL(sendList.get(0));
			conn = (HttpURLConnection) url.openConnection();
			conn.setDoOutput(true);
			conn.setRequestMethod("POST");
			conn.setRequestProperty("Content-type", "application/json");
			conn.setRequestProperty("Accept-Charset", "UTF-8");
			conn.setConnectTimeout(10000);
			conn.setReadTimeout(90000);

			os = conn.getOutputStream();
			os.write(jsonValue.getBytes("UTF-8"));
			os.flush();

			System.out.println("Response code: " + conn.getResponseCode()+" " + conn.getResponseMessage());

			if (conn.getResponseCode() >= 200 && conn.getResponseCode() <= 300) {
				rd = new BufferedReader(new InputStreamReader(conn.getInputStream(), "UTF-8"));
			} else {
				rd = new BufferedReader(new InputStreamReader(conn.getErrorStream(), "UTF-8"));
			}

			while ((line = rd.readLine()) != null) {
				System.out.println(line);
				sb.append(line);
			}
			
			rd.close();
			os.close();
			conn.disconnect();
		} catch (Exception e) {
			throw new BizException("sendREST JSON전송 오류 \n\n\n"+e);
		} finally {
			if(rd != null ) try {rd.close(); } catch (IOException e) {e.printStackTrace(); }
			if(os != null ) try {os.close(); } catch (IOException e) {e.printStackTrace(); } 
			if(conn != null ) conn.disconnect(); 
			sendList.remove(sendURL);
		}
		return sb.toString();
	}
	
	/* 22.05.03 
	 * (현재사용X)
	 * */
	@SuppressWarnings("unchecked")
	public MMultiData semiSet(MData mParamDataList) throws Exception {
		String resourceType = mParamDataList.get("resourceType").toString();
		String patientNumber = mParamDataList.get("patientNumber").toString();
		String patientName = mParamDataList.get("patientName").toString();
		String birthDate = mParamDataList.get("birthDate").toString();
		String patientPhone = mParamDataList.get("patientPhone").toString();

		String sendURL = mParamDataList.get("sendURL").toString();
		String managerId = mParamDataList.get("managerId").toString();
		String careAgencyCode = mParamDataList.get("careAgencyCode").toString();
		String centerCode = mParamDataList.get("centerCode").toString();
		String encKey = mParamDataList.get("encKey").toString();
		String startDate = mParamDataList.get("startDate").toString();
		String endDate = mParamDataList.get("endDate").toString();
		//Long timer = Long.parseLong((String) mParamDataList.get("timer"));
		int rstDiff = Integer.parseInt(mParamDataList.get("rstDiff").toString());

		String param = managerId + "|" + careAgencyCode + "|" + getNow();
		String token = aesEncrypt(encKey, IV, param); // 토큰 생성 및 암호화---
	
		JSONObject jObject = new JSONObject();
		MData mResultData = new MData();
		
		jObject.put("resourceType", resourceType);
		
		jObject.put("patientNumber", patientNumber);// 62817236
		jObject.put("patientName", patientName); 	// 홍길동
		jObject.put("birthDate", birthDate); 		// 19930516
		jObject.put("patientPhone", patientPhone);  // 01033337777
		jObject.put("startDate", startDate); 		// 20220101
		jObject.put("endDate", endDate); 			// 20220131
		
		jObject.put("token", token);
		jObject.put("managerId", managerId);
		jObject.put("centerCode", centerCode);
		jObject.put("careAgencyCode", careAgencyCode);
		//jObject.put("timer", timer);
		
//		String[] resourceTypeArray = { "bloodSugar","pulseRate" };
		String[] resourceTypeArray = {"patientInfo","survey","symptom",
		"temperature","bloodPressure","bloodSugar",
		"oxygenSaturation","pulseRate","clinicMemo"
		};
		
		for (int rtCnt = 0; rtCnt < resourceTypeArray.length; rtCnt++) {
			//리소스 로직 처리 CNT 상태
			int cnt = 0;
			
			jObject.replace("resourceType", resourceTypeArray[rtCnt]);
			
			//String jsonValue = jObject.toJSONString();
			//String result = sendREST(sendURL, jsonValue); // 데이터 송수신---
			String result ="";
			
			for(int day=0; day<=rstDiff; day++) {
				Calendar cal = Calendar.getInstance();
				DateFormat df = new SimpleDateFormat("yyyyMMdd");
				
		        cal.setTime(df.parse(startDate));
		        cal.add(Calendar.DATE, day);
		        jObject.replace("startDate", df.format(cal.getTime()));
		        jObject.replace("endDate", df.format(cal.getTime()));
		        
		        String jsonValue = jObject.toJSONString();
		        result = sendREST(sendURL, jsonValue); // 데이터 송수신---
		        
		        cnt += semiInsertData((String) jObject.get("resourceType"), result);
			}
			mResultData.set((String) jObject.get("resourceType"), cnt);
			System.out.println((String) jObject.get("resourceType") + " 종료");
		}
		return MMultiDataUtil.setResult(mResultData);
	}
	
	/* 22.05.03 
	 * (현재사용X)
	 * 일괄처리 타임테스크 쓰레드 분기점
	 * */
	@SuppressWarnings("unchecked")
	public MMultiData totSet(MData mParamDataList) throws Exception {
		String resourceType = mParamDataList.get("resourceType").toString();
		String patientNumber = mParamDataList.get("patientNumber").toString();
		String patientName = mParamDataList.get("patientName").toString();
		String birthDate = mParamDataList.get("birthDate").toString();
		String patientPhone = mParamDataList.get("patientPhone").toString();

		String sendURL = mParamDataList.get("sendURL").toString();
		String managerId = mParamDataList.get("managerId").toString();
		String careAgencyCode = mParamDataList.get("careAgencyCode").toString();
		String centerCode = mParamDataList.get("centerCode").toString();
		String encKey = mParamDataList.get("encKey").toString();
		String startDate = mParamDataList.get("startDate").toString();
		String endDate = mParamDataList.get("endDate").toString();
		Long timer = Long.parseLong((String) mParamDataList.get("timer"));
		int rstDiff = Integer.parseInt(mParamDataList.get("rstDiff").toString());

		String param = managerId + "|" + careAgencyCode + "|" + getNow();
		String token = aesEncrypt(encKey, IV, param); // 토큰 생성 및 암호화---

		/*
		 * patientInfo : 환자정보 
		 * survey : 문진 정보 
		 * symptom : 환자 임상정보 
		 * temperature : 체온
		 * bloodPressure : 혈압 
		 * bloodSugar : 혈당 
		 * oxygenSaturation : 산소포화도 
		 * pulseRate : 맥박수
		 * clinicMemo : 의료진 메모
		 */
		JSONObject jObject = new JSONObject();

		jObject.put("resourceType", resourceType);
		jObject.put("patientNumber", patientNumber);// 62817236
		jObject.put("patientName", patientName);    // 홍길동
		jObject.put("birthDate", birthDate); 	    // 19930516
		jObject.put("patientPhone", patientPhone);  // 01033337777
		jObject.put("startDate", startDate); 	    // 20220101
		jObject.put("endDate", endDate); 		    // 20220131
		
		jObject.put("token", token);
		jObject.put("managerId", managerId);
		jObject.put("centerCode", centerCode);
		jObject.put("careAgencyCode", careAgencyCode);
		jObject.put("timer", timer);

		String jsonValue;
		MData mResultData = new MData();
		
		// 리소스 로직 처리 CNT 상태
		// -1 : 일괄자동실행 중인 상태
		//  0 : 일괄자동중지 및  초기화
		//0이상: 수동버튼클릭시에만 반응(건수가 찍혀서 출력)
		int cnt = 0;
		if (resourceType.equals("allDown")) {
			isertAllTot(sendURL, jObject);
			cnt = -1;
		} else if (resourceType.equals("allDownStop")) {
			isertAllTotStop(jObject);
			cnt = 0;
		} else { // 전체이면
			Calendar cal = Calendar.getInstance();
			DateFormat df = new SimpleDateFormat("yyyyMMdd");
	
			for(int day=0; day<=rstDiff; day++) {
			    cal.setTime(df.parse(startDate));
			    cal.add(Calendar.DATE, day);
			       
			    jObject.replace("startDate", df.format(cal.getTime()));
			    jObject.replace("endDate", df.format(cal.getTime()));
			        
			    jsonValue = jObject.toJSONString();
			       
			    String result = sendREST(sendURL, jsonValue); // 데이터 송수신---
					
			    cnt += semiInsertData(resourceType, result);
			}
			mResultData.set((String) jObject.get("resourceType"), cnt);
		}
		return MMultiDataUtil.setResult(mResultData);
	}

	/* 22.05.03 
	 * (현재사용중)
	 * List 저장후 매퍼들어가기전 분기점
	 * */
	@SuppressWarnings({ "unchecked", "rawtypes" })
	private int semiInsertData(String resourceType, String result) throws ParseException {
		// Process process = Runtime.getRuntime().exec(data);
		JSONParser jsonParse = new JSONParser();
		JSONArray jarr = new JSONArray();
		int cnt = 0;
		
		try {
			jarr = (JSONArray) jsonParse.parse(result);
		}catch (Exception e) {
			//processException("json 파싱오류 : \n"+result);
			//leaveaTrace("json 파싱오류 : \n"+result);
			//throw new BizException("json 파싱오류 : \n"+result);
			return -1;
		}
		
		//JSONObject jobj = new JSONObject();
		List list = new ArrayList();
		Map<String, Object> map = new HashMap<String, Object>();
		 
		for (int i = 0; i < jarr.size(); i++) {
			//JSONObject jobj = (JSONObject) jsonParse.parse(jarr.get(i).toString());
			//getMapFromJsonObject((JSONObject) jsonParse.parse(jarr.get(i).toString()));
			// JSONObject jobj = (JSONObject) jsonParse.parse(new InputStreamReader(process.getInputStream()));

			//MData dataSet = new MData();
			//JOSN obj MAP 타입 제네릭변경
			//dataSet.addRow(getMapFromJsonObject(jobj));
			//resourceTypeCp = (String) jobj.get("resourceType");
			list.add(getMapFromJsonObject((JSONObject) jsonParse.parse(jarr.get(i).toString())));
		}
	
		if(list.size() > 0) {
			map.put("lists",list);
			
			//리스폰시 환자정보 patientInfo'List' 들어와서 변경
			if (resourceType.equals("patientInfo")) {
				try {
					cnt += insertPatientInfoList(map);
				}catch (Exception e){
					throw new BizException("생활치료환자정보 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("survey")) {
				try {
					cnt += insertSurvey(map);
				}catch (Exception e){
					throw new BizException("생활치료문진정보 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("symptom")) {
				try {
					cnt += insertSymptom(map);
				}catch (Exception e){
					throw new BizException("생활치료증상정보 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("temperature")) {
				try {
					cnt += insertTemperature(map);
				}catch (Exception e){
					throw new BizException("생활치료체온 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("bloodPressure")) {
				try {
					cnt += insertBloodPressure(map);
				}catch (Exception e){
					throw new BizException("생활치료혈압 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("bloodSugar")) {
				try {
					cnt += insertBloodSugar(map);
				}catch (Exception e){
					throw new BizException("생활치료혈당 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("oxygenSaturation")) {
				try {
					cnt += insertOxygenSaturation(map);
				}catch (Exception e){
					throw new BizException("생활치료산소포화도 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("pulseRate")) {
				try {
					cnt += insertPulseRate(map);
				}catch (Exception e){
					throw new BizException("생활치료맥박수 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("clinicMemo")) {
				try {
					cnt += insertClinicMemo(map);
				}catch (Exception e){
					throw new BizException("생활치료의료진 에서 오류 \n\n\n"+e);
				}
			}
		}	
		return cnt;
		
		/*
		 * create table 생활치료환자정보( 환자등록번호 VARCHAR2(15), 환자이름 VARCHAR2(15), 성별 VARCHAR2(5), 
		 * 동 VARCHAR2(5), 호 VARCHAR2(5), 퇴소일자 DATE, 입소일자 DATE, 
		 * 상태 VARCHAR2(5), --H:입소, D:퇴원, T:지정병원이송, E:기타 
		 * 센터코드 VARCHAR2(5), 생년월일 DATE, 환자전화번호 VARCHAR2(15), 보호자전화번호 VARCHAR2(15), 
		 * 증상시작일 DATE, 확진일 DATE, 상태변경일 DATE, 기저질환유무 VARCHAR2(1), 최근약복용유무 VARCHAR2(1), 
		 * 약이름 VARCHAR2(15), 임신유무 VARCHAR2(1), 임신주차 VARCHAR2(5), 담당의료진 VARCHAR2(15), 
		 * 퇴소예정일 DATE ) --수정하여 사용하세요! 
		 * TABLESPACE DBSPACE4 PCTFREE 10 PCTUSED 90 STORAGE (INITIAL 300k NEXT 100K PCTINCREASE 0);
		 * 
		 * CREATE UNIQUE INDEX 생활치료환자정보_01 ON 생활치료환자정보 ( 환자등록번호, 환자이름, 생년월일, 환자전화번호 )
		 * --바이탈쪽 테이블만들때는 인덱스에 입력시간 포함 --수정하여 사용하세요! 
		 * PCTFREE 5 TABLESPACE IDBSPACE4 STORAGE (INITIAL 100K NEXT 50K PCTINCREASE 0);
		 */
	}
	
	/* 22.05.03 
	 * (현재사용X)
	 * */
	public int fInsertData(MData dataSet, String resourceType) {
		Map<String, Object> map = new HashMap<String, Object>();
		List<Map<String, Object>> mParamAsList = dataSet.getRowAsMap();
		
		int cnt = 0;
		if(mParamAsList.size() > 0) {
			map.put("lists", mParamAsList);
			
			//리스폰시 환자정보 patientInfo'List' 들어와서 변경
			if (resourceType.equals("patientInfo")) {
				try {
					cnt += insertPatientInfoList(map);
				}catch (Exception e){
					throw new BizException("생활치료환자정보 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("survey")) {
				try {
					cnt += insertSurvey(map);
				}catch (Exception e){
					throw new BizException("생활치료문진정보 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("symptom")) {
				try {
					cnt += insertSymptom(map);
				}catch (Exception e){
					throw new BizException("생활치료증상정보 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("temperature")) {
				try {
					cnt += insertTemperature(map);
				}catch (Exception e){
					throw new BizException("생활치료체온 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("bloodPressure")) {
				try {
					cnt += insertBloodPressure(map);
				}catch (Exception e){
					throw new BizException("생활치료혈압 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("bloodSugar")) {
				try {
					cnt += insertBloodSugar(map);
				}catch (Exception e){
					throw new BizException("생활치료혈당 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("oxygenSaturation")) {
				try {
					cnt += insertOxygenSaturation(map);
				}catch (Exception e){
					throw new BizException("생활치료산소포화도 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("pulseRate")) {
				try {
					cnt += insertPulseRate(map);
				}catch (Exception e){
					throw new BizException("생활치료맥박수 에서 오류 \n\n\n"+e);
				}
			} else if (resourceType.equals("clinicMemo")) {
				try {
					cnt += insertClinicMemo(map);
				}catch (Exception e){
					throw new BizException("생활치료의료진 에서 오류 \n\n\n"+e);
				}
			}
		}	
		return cnt;
	}

	/* 22.05.03 
	 * (현재사용X)
	 * 스케쥴링 시간설정, 쓰레드작동
	 * */
	private void isertAllTot(String sendURL, JSONObject jObject) {
		task = creatTimerTask(sendURL, jObject);
		Timer scheduler = new Timer();
		
		// 1초 뒤 1분 or 10분 마다 반복실행
		scheduler.scheduleAtFixedRate(task, 1000, (long) jObject.get("timer"));
	}
	
	/* 22.05.03 
	 * (현재사용X)
	 * 타임테스크가 NEW 생성 후 해당안 모듈화되어있는 상태
	 * 부르는 작업만 하면 반복실행
	 * */
	private TimerTask creatTimerTask(String sendURL, JSONObject jObject) {
		TimerTask task = new TimerTask() {
			@SuppressWarnings("unchecked")
			@Override
			public void run() {
				String[] resourceTypeArray = { "bloodPressure", "bloodSugar" };
				
//				String[] resourceTypeArray = {"patientInfo","survey","symptom",
//				"temperature","bloodPressure","bloodSugar",
//				"oxygenSaturation","pulseRate","clinicMemo"
//				};

				for (int rtCnt = 0; rtCnt < resourceTypeArray.length; rtCnt++) {
					jObject.replace("resourceType", resourceTypeArray[rtCnt]);

					String jsonValue = jObject.toJSONString();
					String result = sendREST(sendURL, jsonValue); // 데이터 송수신---

					try {
						semiInsertData((String) jObject.get("resourceType"), result);
					} catch (ParseException e) {
						// TODO Auto-generated catch block
						throw new BizException("타임테스크 오류 \n\n\n"+e);
					} finally {
						System.out.println((String) jObject.get("resourceType") + " 종료");
					}

				}

			}
		};
		return task;
	}
	
	/* 22.05.03 
	 * (현재사용X)
	 * 스케쥴링 스탑, 쓰레드 캔슬
	 * */
	private void isertAllTotStop(JSONObject jObject) {
		task.cancel();
	}
	

	private int insertClinicMemo(Map<String, Object> map) {
		return lifeCenterMapper.insertClinicMemo(map);
	}

	private int insertPulseRate(Map<String, Object> map) {
		return lifeCenterMapper.insertPulseRate(map);
	}

	private int insertOxygenSaturation(Map<String, Object> map) {
		return lifeCenterMapper.insertOxygenSaturation(map);
	}

	private int insertBloodSugar(Map<String, Object> map) {
		return lifeCenterMapper.insertBloodSugar(map);
	}

	private int insertBloodPressure(Map<String, Object> map) {
		return lifeCenterMapper.insertBloodPressure(map);
	}

	private int insertTemperature(Map<String, Object> map) {
		return lifeCenterMapper.insertTemperature(map);
	}

	private int insertSymptom(Map<String, Object> map) {
		return lifeCenterMapper.insertSymptom(map);
	}

	private int insertSurvey(Map<String, Object> map) {
		return lifeCenterMapper.insertSurvey(map);
	}

	private int insertPatientInfoList(Map<String, Object> map) {
		return lifeCenterMapper.insertPatientInfoList(map);
	}

	public MData selectTabList(MData mData) {
		return lifeCenterMapper.selectTabList(mData);
	}

	public MData selectLifeGicho() {
		return lifeCenterMapper.selectLifeGicho();
	}

	public MData selectBoardDetailList(MData mParamDataList) {
		return lifeCenterMapper.selectBoardDetailList(mParamDataList);
	}
	
	public MData selectPatientInfoList(MData mParamDataList) {
		return lifeCenterMapper.selectPatientInfoList(mParamDataList);
	}
	
	public MData selectLostNumList(MData mData) {
		return  lifeCenterMapper.selectLostNumList(mData);
	}
	
	/* 22.05.03 
	 * (현재사용중)
	 * 연계정보 EMR 입력
	 * */
	public void saveEmrData(MData mParamDataList) {
		String vdate = mParamDataList.get("vdate").toString();
		String bdays = mParamDataList.get("bdays").toString();
		String vjid = mParamDataList.get("vjid").toString();
		
		Map<String, Object> map = new HashMap<String, Object>();
		map.put("vdate", vdate);
		map.put("bdays", bdays);
		map.put("vjid", vjid);
		
		try {
			lifeCenterMapper.saveEmrData(map);
		}catch (Exception e) {
			throw new BizException("covid19ToEmr 프로시저 오류 \n\n\n"+e);
		}
	}

	public int updateLostNum(MData mData) {
		return lifeCenterMapper.updateLostNum(mData);
	}
	

}
