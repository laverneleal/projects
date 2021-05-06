unit Unit1;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, mysql56conn, sqldb, db, Forms, Controls, Graphics, Dialogs,
  StdCtrls, FileUtil, ShellAPI, Types, jsonparser, fpjson;

type

  { TForm1 }

  TForm1 = class(TForm)
    Button1: TButton;
    Button2: TButton;
    DataSource1: TDataSource;
    DataSource2: TDataSource;
    Label1: TLabel;
    Memo1: TMemo;
    Memo2: TMemo;
    MySQL56Connection1: TMySQL56Connection;
    MySQL56Connection2: TMySQL56Connection;
    SQLQuery1: TSQLQuery;
    SQLQuery2: TSQLQuery;
    SQLTransaction1: TSQLTransaction;
    SQLTransaction2: TSQLTransaction;
    procedure Button1Click(Sender: TObject);
    procedure Button2Click(Sender: TObject);
    procedure FormActivate(Sender: TObject);
    procedure FormClose(Sender: TObject; var CloseAction: TCloseAction);
    procedure FormCreate(Sender: TObject);
  private

  public

  end;

var
  Form1: TForm1;
    function getBossID( sCustomerCode:string; splanNo:string; sBossCode:string; additionalRequest:string; pdfData:string  ): string;
    procedure BOSSSending( xHRDId, xCustomerCode, xplanNo, xBossDue, xPlannerCode, xReceivedDay, xpdfData : string);
    procedure UpdateHPPlanJobs( hpPlanJobsId:string );
    procedure UpdateHPPlanTasks( hpPlanTaskId:string );
    procedure CADImanagerDatabase( HRDId:string; Bundle:string; Dept:string; Section:string; Team:string; Incharge:string; sDate:string; sIp:string );
    procedure Movefiles(xCustomerCode:string; xpdfData:string);
    procedure RemoveFiles(xCustomerCode:string; xpdfData:string);
    Procedure UpdateEmailedPlan( customerCode:string; planNumber:string; response:string; sFile:string );
    procedure CompletePlans( xHRDId:string; xCustomerCode:string; xplanNo:string; xBossDue:string; xPlannerCode:string; xReceivedDay:string; xpdfData:string );
    procedure Delay(AMiliSeconds: DWORD);
    procedure DeploySender();
implementation

uses
  fphttpClient;

const
  FILE_SERVER = '\\Fs-pira\finished plans\';
  PDF_SERVER = '\\shiyousho.hrd-s.com\dc-svr\pdf\';
  C_SERVER_BOSSMAIL   = 'http://hrdapps48:9011/hrdbossmailapi';
  C_UPLOAD       = '/sendupload';
{$R *.lfm}

{ TForm1 }

procedure TForm1.Button1Click(Sender: TObject);
var
   PascalFiles: TStringList;
   pdfData, hrdid : string;
   i,x,d : integer;
   sRevisionNos, XXX: TStringArray;
   rev1, rev2, rev3, rev4, sRevisionNo, sRevisionNo2, sRevisionNo3, sRev1, sRev2, sRev3 ,
   splanNo, sCustomerCode, sBossCode, sRev4, PlanJobValues, sVal : string;
   xId, xId2, xHRDId, xCustomerCode, xplanNo, xsBossCode, xPlannerCode, xReceivedDay, xBossDue, xRemarks, xadditionalRequest, xpdfData, xBundle : string;
begin
try
   PascalFiles := TStringList.Create;
   FindAllFiles(PascalFiles, '\\10.168.64.156\finished plans\HP FINISHED PDF\Compiler_Trial\' , 'HP-*.pdf' , true);
   memo1.Lines.Text:= PascalFiles.Text;
   d:=Memo1.lines.count;
   x := 1;
   for i:=0 to d-1 do
   begin
     pdfData:=extractFileName(memo1.lines[i]);

     sRevisionNos := pdfData.Split('-');
      rev1 := sRevisionNos[1];
      rev2 := sRevisionNos[2];
      rev3 := sRevisionNos[3];
      rev4 := sRevisionNos[4];

      if(rev4 = '') then
          begin
               sRev4 := '';
               setlength( rev3, length(rev3) - 4);
          end
       else
           begin
                setlength( rev4, length(rev4) - 4);
                sRev4 := Copy(rev4, 3, 1);
                rev3 := sRevisionNos[3];
           end;

      sRevisionNo := Copy(rev3, 1, 2);
      if( strToInt(sRevisionNo) > 9) then
          sRev1 := sRevisionNo
      else
          sRev1 := Copy(rev3, 2, 1);

      sRevisionNo2 := Copy(rev3, 3, 2);
      if( strToInt(sRevisionNo2) > 9) then
          sRev2 := sRevisionNo2
      else
          sRev2 := Copy(rev3, 4, 1);

      sRevisionNo3 := Copy(rev3, 5, 1);
       if(sRevisionNo3 = '') then
           sRev3 := ''
       else
           sRev3 := sRevisionNo3;

    sCustomerCode := rev1 + '-' + rev2;
    sBossCode := '042';
    splanNo := sRev1 + '-' + sRev2 + sRevisionNo3;

    PlanJobValues := getBossID(sCustomerCode, splanNo, sBossCode, sRev4, pdfData );
    XXX := PlanJobValues.Split('*');
    xId                := XXX[0];
    xId2               := XXX[1];
    xHRDId             := XXX[2];
    xCustomerCode      := XXX[3];
    xplanNo            := XXX[4];
    xsBossCode         := XXX[5];
    xPlannerCode       := XXX[6];
    xReceivedDay       := FormatDateTime('yyyy/mm/dd hh:nn', strToDateTime(XXX[7]));
    xBossDue           := FormatDateTime('yyyy/mm/dd hh:nn', strToDateTime(XXX[8]));
    xRemarks           := XXX[9];
    xadditionalRequest := XXX[10];
    xpdfData           := XXX[11];
    xBundle            := XXX[12];

{ ----------------------------------------------------- }
{ PROCESS FOR SENDING TO BOSS AND UPDATED ALL DATABASES }
{ ----------------------------------------------------- }
    delay(4000);
    BOSSSending( xHRDId, xCustomerCode, xplanNo, xBossDue, xPlannerCode, xReceivedDay, xpdfData );
    UpdateHPPlanJobs( xId2);
    UpdateHPPlanTasks( xId );
    Movefiles(xCustomerCode, xpdfData);
    CADImanagerDatabase( xHRDId, xBundle, 'House Presentation', 'HP Support' , 'Distri', '30622', FormatDateTime('yyyy/mm/dd hh:nn', Now), '10.168.130.200' );
    CompletePlans( xHRDId, xCustomerCode, xplanNo, xBossDue, xPlannerCode, xReceivedDay, xpdfData);
    delay(2000);
    RemoveFiles (xCustomerCode, xpdfData);
   end;

except
   On E :Exception do begin
      ShowMessage(E.Message);
    end;
end;

end;

procedure TForm1.Button2Click(Sender: TObject);
var
   PascalFiles: TStringList;
label A;
begin
  Form1.Memo2.Text:='';
  delay(10000);

  PascalFiles := TStringList.Create;
  A:
  FindAllFiles(PascalFiles, '\\10.168.64.156\finished plans\HP FINISHED PDF\Compiler_Trial\' , 'HP-*.pdf' , false);
  if( PascalFiles.Text <> '' ) then
       begin
           Form1.Button1.Click;
           Delay(5000);
           Goto A;
       end
  else
      begin
          Delay(5000);
          Goto A;
      end;
end;

procedure TForm1.FormActivate(Sender: TObject);
var
   PascalFiles: TStringList;
label A;
begin
  Form1.Memo2.Text:='';
  delay(10000);

  PascalFiles := TStringList.Create;
  A:
  FindAllFiles(PascalFiles, '\\10.168.64.156\finished plans\HP FINISHED PDF\Compiler_Trial\' , 'HP-*.pdf' , false);
  if( PascalFiles.Text <> '' ) then
       begin
           Form1.Button1.Click;
           Delay(5000);
           Goto A;
       end
  else
      begin
          Delay(5000);
          Goto A;
      end;
  //DeploySender();

end;

{ ---------------------------------------- }
{ SAVING ALL PLANS THAT SEND INTO DATABASE }
{ ---------------------------------------- }
procedure CompletePlans( xHRDId:string; xCustomerCode:string; xplanNo:string; xBossDue:string; xPlannerCode:string; xReceivedDay:string; xpdfData:string );
var
  sqlText : string;
begin

   Form1.SQLQuery1.SQL.Clear ;
   Try
   Form1.MySQL56Connection1.Connected:=true;
   sqlText := 'INSERT INTO completeplans( hrdid, customerCode, planNo, bossDue, plannerCode, recievedDay, pdfData ) VALUES ( :sHRDId, :sCustomerCode, :splanNo, :sBossDue, :sPlannerCode, :sReceivedDay, :spdfData )';
   Form1.SQLQuery1.SQL.Text := sqlText;
   Form1.SQLQuery1.Params.ParamByName('sHRDId').AsString := Trim(xHRDId);
   Form1.SQLQuery1.Params.ParamByName('sCustomerCode').AsString := Trim(xCustomerCode);
   Form1.SQLQuery1.Params.ParamByName('splanNo').AsString := Trim(xplanNo);
   Form1.SQLQuery1.Params.ParamByName('sBossDue').AsString := Trim(xBossDue);
   Form1.SQLQuery1.Params.ParamByName('sPlannerCode').AsString := Trim(xPlannerCode);
   Form1.SQLQuery1.Params.ParamByName('sReceivedDay').AsString := Trim(xReceivedDay);
   Form1.SQLQuery1.Params.ParamByName('spdfData').AsString := Trim(xpdfData);
   Form1.SQLQuery1.ExecSQL;
   Form1.SQLTransaction1.Commit;

   finally
     Form1.SQLQuery1.Close;
     Form1.SQLTransaction1.Active:= False;
     Form1.SqlQuery1.SQLConnection.Connected:=false;
   end;
end;

{ ---------------------------- }
{ GETTING HRDID ON HP DATABASE }
{ ---------------------------- }
function getBossID( sCustomerCode:string; splanNo:string; sBossCode:string; additionalRequest:string; pdfData:string ): string;
var
  Id, Id2, HRDId, ShioNo, PlannerCode, SalesmanCode, ReceivedDay, BossDue, Remarks, BossCode, Bundle : TField;
begin
    try
      if not( Form1.MySQL56Connection1.Connected) then Form1.MySQL56Connection1.Open;
    begin
      Form1.SQLQuery1.SQL.Text := 'SELECT * FROM hpplanjobs WHERE ConstructionCode LIKE :ConstructionCodeT AND PlanNo LIKE :PlanNoT AND PlanType LIKE :additionalRequestT AND BossCode LIKE :BossCodeT AND DateDeleted IS NULL ORDER BY Id ASC';
      Form1.MySQL56Connection1.Connected := True;
      Form1.DataSource1.DataSet := Form1.SQLQuery1;
      Form1.SQLTransaction1.Active := True;
      Form1.SQLQuery1.Prepare;
      Form1.SQLQuery1.Params.ParamByName('ConstructionCodeT').AsString := ''+ Trim(sCustomerCode) +'';
      Form1.SQLQuery1.Params.ParamByName('PlanNoT').AsString := ''+ Trim(sPlanNo) +'';
      Form1.SQLQuery1.Params.ParamByName('BossCodeT').AsString := ''+ Trim(sBossCode) +'';
      Form1.SQLQuery1.Params.ParamByName('additionalRequestT').AsString := '%'+ Trim(additionalRequest) +'%';
    end;
      Form1.SQLQuery1.Open;
      Id := Form1.SQLQuery1.FieldByName('Id');
      HRDId:= Form1.SQLQuery1.FieldByName('HRDId');
      ShioNo:= Form1.SQLQuery1.FieldByName('ShioNo');
      PlannerCode:= Form1.SQLQuery1.FieldByName('PlannerCode');
      SalesmanCode:= Form1.SQLQuery1.FieldByName('SalesmanCode');
      BossCode:= Form1.SQLQuery1.FieldByName('BossCode');
      ReceivedDay:= Form1.SQLQuery1.FieldByName('ReceivedDay');
      BossDue:= Form1.SQLQuery1.FieldByName('BossDue');
      Remarks:= Form1.SQLQuery1.FieldByName('Remarks');

    finally
       result := Id.AsString + '*' + HRDId.AsString + '*' +sCustomerCode+ '*' + splanNo + '*' + sBossCode + '*'  + PlannerCode.AsString + '*' + ReceivedDay.AsString + '*' + BossDue.AsString + '*' + Remarks.AsString + '*' +  additionalRequest + '*' + pdfData ;
       Form1.SQLQuery1.Close;
       Form1.SQLTransaction1.Active:= False;
       Form1.SqlQuery1.SQLConnection.Connected:=false;
    end;

    try
      if not( Form1.MySQL56Connection1.Connected) then Form1.MySQL56Connection1.Open;
    begin
      Form1.SQLQuery1.SQL.Text := 'SELECT * FROM hpplantasks WHERE ConstructionCode LIKE :ConstructionCodeT AND PlanNo LIKE :PlanNoT AND PlanType LIKE :additionalRequestT AND ProcessType LIKE :ProcessTypeT AND DateDeleted IS NULL ORDER BY Id ASC';
      Form1.MySQL56Connection1.Connected := True;
      Form1.DataSource1.DataSet := Form1.SQLQuery1;
      Form1.SQLTransaction1.Active := True;
      Form1.SQLQuery1.Prepare;
      Form1.SQLQuery1.Params.ParamByName('ConstructionCodeT').AsString := ''+ Trim(sCustomerCode) +'';
      Form1.SQLQuery1.Params.ParamByName('PlanNoT').AsString := ''+ Trim(sPlanNo) +'';
      Form1.SQLQuery1.Params.ParamByName('ProcessTypeT').AsString := ''+ 'HP Mail Sending' +'';
      Form1.SQLQuery1.Params.ParamByName('additionalRequestT').AsString := '%'+ Trim(additionalRequest) +'%';
    end;
      Form1.SQLQuery1.Open;
      Id2 := Form1.SQLQuery1.FieldByName('Id');
      Bundle := Form1.SQLQuery1.FieldByName('Bundle');

    finally
       result := Id2.AsString + '*' + result + '*' + Bundle.AsString;
       Form1.SQLQuery1.Close;
       Form1.SQLTransaction1.Active:= False;
       Form1.SqlQuery1.SQLConnection.Connected:=false;
    end;
end;

{ ----------------------------- }
{ UPDATE HP PLAN TASKS DATABASE }
{ ----------------------------- }
procedure BOSSSending( xHRDId, xCustomerCode, xplanNo, xBossDue, xPlannerCode, xReceivedDay, xpdfData : string);
var
   sVals : TStringArray;
   Client: TFPHttpClient;
   FormData: TStringList;
   URL, hrdId, customerCode, planNumber, plannerCode, receivedDay, finishPlanDay, remarks, finishedDay, sfile : String;
   MyTime: TDateTime;
   Respo: TStringStream;
   response: string;

   datos: string;
   Data: TJSONArray;
   DataArrayItem: TJSONObject;
   response2, status: String;

begin
   MyTime:= Now;

   hrdId         := xHRDId;
   customerCode  := xCustomerCode;
   planNumber    := xplanNo;
   plannerCode   := xPlannerCode;
   receivedDay   := xReceivedDay;
   finishPlanDay := xBossDue;
   remarks       := '--';
   finishedDay   := FormatDateTime('yyyy/mm/dd hh:nn', Now);
   sfile         := xpdfData;

   begin
     FormData := TStringList.Create;
     try
       FormData.Values['customerCode']     := customerCode;
       FormData.Values['plannerCode']      := plannerCode;
       FormData.Values['planNumber']       := planNumber;
       FormData.Values['hrdId']            := hrdId;
       FormData.Values['planType']         := '042';
       FormData.Values['receivedDay']      := receivedDay;
       FormData.Values['finishPlanDay']    := finishPlanDay;
       FormData.Values['finishedDay']      := finishedDay;
       FormData.Values['remarks']          := '--';

       Client := TFPHttpClient.Create(nil);
       Respo := TStringStream.Create('');

       try
          URL := C_SERVER_BOSSMAIL + C_UPLOAD;
          Client.FileFormPost( URL, FormData, 'file', '\\Fs-pira\finished plans\HP FINISHED PDF\Compiler_Trial\' + sFile , Respo );

       response := respo.DataString;

       response2 := '[' +response+ ']';
       Data := TJSONArray(GetJSON(response2));
       DataArrayItem := Data.Objects[0];
       status := DataArrayItem['status'].AsString;
        if ( status = 'OK' ) then
           begin
               UpdateEmailedPlan( customerCode, planNumber, response, sFile );
               exit;
           end
        else
            begin
                UpdateEmailedPlan( customerCode, planNumber, response, sFile );
                Form1.Button1.Click;
            end;

{---------------}
{       if NOT( Copy(response.substring(response.length-4), 1, 2) = 'OK' ) then
          begin
               UpdateEmailedPlan( customerCode, planNumber, response, sFile );
               exit;
          end
       else
           begin
                UpdateEmailedPlan( customerCode, planNumber, response, sFile );
           end;
}
{---------------}
       finally
         Client.Free;
       end;

     finally
       Form1.Memo2.Lines.AddStrings( sFile );
       FormData.Free;
     end;
end;

end;
procedure UpdateHPPlanTasks( hpPlanTaskId:string );
var
  sqlText, sCol : string;
begin

   Form1.SQLQuery1.Close;
   try
   Form1.SQLQuery1.SQL.Clear ;
   Form1.MySQL56Connection1.Connected:=true;
   sqlText := 'UPDATE hpplantasks SET Start=:finishz,Finished=:finishz,Status="Emailed" WHERE Id=:ids';
   Form1.SQLQuery1.SQL.Text := sqlText;
   Form1.SQLQuery1.Params.ParamByName('ids').AsInteger := strToInt(hpPlanTaskId);
   Form1.SQLQuery1.Params.ParamByName('finishz').AsString := FormatDateTime('YYYY/MM/DD hh:nn:ss', Now); //sDate;

   Form1.SQLQuery1.ExecSQL;
   Form1.SQLTransaction1.CommitRetaining;

   finally
     Form1.SQLQuery1.Close;
     Form1.SQLTransaction1.Active:= False;
     Form1.SqlQuery1.SQLConnection.Connected:=false;

   end;
end;

{ --------------------------- }
{ UPDATE PALN ON HP PLAN JOBS }
{ --------------------------- }
procedure UpdateHPPlanJobs( hpPlanJobsId:string );
var
  sqlText, sCol : string;
begin

   Form1.SQLQuery1.Close;
   try
   Form1.SQLQuery1.SQL.Clear ;
   Form1.MySQL56Connection1.Connected:=true;
   sqlText := 'UPDATE hpplanjobs SET HPFinished=:finishz WHERE Id=:ids ';
   Form1.SQLQuery1.SQL.Text := sqlText;
   Form1.SQLQuery1.Params.ParamByName('ids').AsInteger := strToInt(hpPlanJobsId);
   Form1.SQLQuery1.Params.ParamByName('finishz').AsString := FormatDateTime('YYYY/MM/DD hh:nn:ss', Now);
   Form1.SQLQuery1.ExecSQL;
   Form1.SQLTransaction1.CommitRetaining;

   finally
     Form1.SQLQuery1.Close;
     Form1.SQLTransaction1.Active:= False;
     Form1.SqlQuery1.SQLConnection.Connected:=false;
   end;
end;

{ ------------------------------------------------------ }
{ UPDATE DATABASE ON XML BUNDLE SCHEDULE ON DAT DATABASE }
{ ------------------------------------------------------ }
procedure CADImanagerDatabase( HRDId:string; Bundle:string; Dept:string; Section:string; Team:string; Incharge:string; sDate:string; sIp:string );
var
  sqlText, sCol : string;
begin
   Form1.SQLQuery2.Close;

   try
   Form1.SQLQuery2.SQL.Clear ;
   Form1.MySQL56Connection2.Connected:=true;
   sqlText := 'UPDATE xml_bundle_schedule_v2 SET bundle=:bundlez, finish_day=:finish_dayz, department=:departmentz, section=:sectionz, team=:teamz, updated_at=:updated_atz, updated_by=:updated_byz, updated_ip=:sipz, updated_by_code="PXF", action_req="UPDATE" WHERE HRD_ID=:ids ';
   Form1.SQLQuery2.SQL.Text := sqlText;
   Form1.SQLQuery2.Params.ParamByName('ids').AsString := HRDId;
   Form1.SQLQuery2.Params.ParamByName('bundlez').AsString := Bundle;
   Form1.SQLQuery2.Params.ParamByName('finish_dayz').AsString := FormatDateTime('YYYY-MM-DD hh:nn:ss', Now);
   Form1.SQLQuery2.Params.ParamByName('departmentz').AsString := Dept;
   Form1.SQLQuery2.Params.ParamByName('sectionz').AsString := Section;
   Form1.SQLQuery2.Params.ParamByName('teamz').AsString := Team;
   Form1.SQLQuery2.Params.ParamByName('updated_atz').AsString := FormatDateTime('YYYY-MM-DD hh:nn:ss', Now); //sDate;
   Form1.SQLQuery2.Params.ParamByName('updated_byz').AsString := Incharge;
   Form1.SQLQuery2.Params.ParamByName('sipz').AsString := sIp;
   Form1.SQLQuery2.ExecSQL;
   Form1.SQLTransaction2.CommitRetaining;

   finally
     Form1.SQLQuery2.Close;
     Form1.SQLTransaction2.Active:= False;
     Form1.SqlQuery2.SQLConnection.Connected:=false;
   end;
end;

{ ------------------------------- }
{ CLOSE CONNECTION FOR FORM CLOSE }
{ ------------------------------- }
procedure TForm1.FormClose(Sender: TObject; var CloseAction: TCloseAction);
begin
     Form1.SQLQuery1.Close;
     Form1.SQLTransaction1.Active:= False;
     Form1.SqlQuery1.SQLConnection.Connected:=false;
end;

{ ---------------------------------------- }
{ SAVING ALL PLANS THAT SEND INTO DATABASE }
{ ---------------------------------------- }
procedure TForm1.FormCreate(Sender: TObject);
begin
   Form1.Width:= 260;
   Form1.Height:= 900;
end;

{ ------------------- }
{ TRIGGER BOSS SENDER }
{ ------------------- }
procedure DeploySender();
var
   PascalFiles: TStringList;
label A;
begin

  PascalFiles := TStringList.Create;
  A:

  FindAllFiles(PascalFiles, '\\10.168.64.156\finished plans\HP FINISHED PDF\Compiler_Trial\' , 'HP-*.pdf' , false);
  if( PascalFiles.Text <> '' ) then
         begin
             Form1.Button1.Click;
             Delay(5000);
             Goto A;
         end
    else
        begin
            Delay(5000);
            Goto A;
        end;
end;

{ ------------------------ }
{ MOVE FILES TO DC-SVR/PDF }
{ ------------------------ }
procedure Movefiles(xCustomerCode:string; xpdfData:string);
var
   sFile : string;
   FileHandle : THandle;
Label A;
begin
  sFile := '\\Fs-pira\finished plans\HP FINISHED PDF\Compiler_Trial\' + xpdfData;
  try
     A:
     FileHandle := FileOpen( sFile, fmOpenRead or fmShareExclusive);
     FileClose(filehandle);
     if ( FileHandle = -1 ) then
        begin
             sleep(100);
             Goto A;
        end
     else
        begin
             RenameFile( sFile, '\\shiyousho.hrd-s.com\dc-svr\pdf\'+ xCustomerCode + '\' + xpdfData);
        end;
  except
    on e:exception do
    exit;
  end;
end;

{---------------------------------------------------}
{                      DELAY                        }
{---------------------------------------------------}
procedure Delay(AMiliSeconds: DWORD);
var
  DW: DWORD;
begin
  DW := GetTickCount;
  while (GetTickCount < DW + AMiliSeconds) and (not Application.Terminated) do begin
      Application.ProcessMessages;
  end;
end;

{ -------------------------- }
{ REMOVE FILES TO DC-SVR/PDF }
{ -------------------------- }
procedure RemoveFiles(xCustomerCode:string; xpdfData:string);
var
  sFile : string;
  FileHandle : THandle;
Label A;
begin
   Delay(10000);
   sFile := '\\Fs-pira\finished plans\HP FINISHED PDF\Compiler_Trial\' + xpdfData;
   try
   A:
     FileHandle := FileOpen( sFile, fmOpenRead or fmShareExclusive);
     FileClose(filehandle);
     if ( FileHandle >= 0) then
        begin
             if FileExists( sFile ) then DeleteFile(sFile);
        end
     else
         begin
              sleep(100);
              Goto A;
         end;
   except
     on e:exception do
     exit;
   end;
end;

{ ------------------------------ }
{ SAVE ALL SEND PLAN INTO DATASE }
{ ------------------------------ }
Procedure UpdateEmailedPlan( customerCode:string; planNumber:string; response:string; sFile:string );
var
  sqlText, sCol : string;
begin

   Form1.SQLQuery1.SQL.Clear ;
   Try
   Form1.MySQL56Connection1.Connected:=true;
   sqlText := 'INSERT INTO emailedplans( ConstructionCode, PlanNo, Email, File ) VALUES ( :customerCodes, :planNumbers, :responses, :sFile )';
   Form1.SQLQuery1.SQL.Text := sqlText;
   Form1.SQLQuery1.Params.ParamByName('customerCodes').AsString := Trim(customerCode);
   Form1.SQLQuery1.Params.ParamByName('planNumbers').AsString := Trim(planNumber);
   Form1.SQLQuery1.Params.ParamByName('responses').AsString := Trim(response);
   Form1.SQLQuery1.Params.ParamByName('sFile').AsString := Trim(sFile);
   Form1.SQLQuery1.ExecSQL;
   Form1.SQLTransaction1.Commit;

   finally
     Form1.SQLQuery1.Close;
     Form1.SQLTransaction1.Active:= False;
     Form1.SqlQuery1.SQLConnection.Connected:=false;
   end;

end;





end.

