unit Unit1;

{$mode objfpc}{$H+}

interface

uses
  Classes, SysUtils, Forms, Controls, Graphics, Dialogs, StdCtrls, FileUtil, ShellAPI;

type

  { TForm1 }

  TForm1 = class(TForm)
    Button1: TButton;
    procedure Button1Click(Sender: TObject);
  private

  public

  end;

var
  Form1: TForm1;
  procedure Delay(AMiliSeconds: DWORD);
implementation

{$R *.lfm}

{ TForm1 }

procedure TForm1.Button1Click(Sender: TObject);
var
   PascalFiles: TStringList;
label A;
begin
  PascalFiles := TStringList.Create;

  A:
  FindAllFiles(PascalFiles, '\\10.168.64.156\finished plans\HP FINISHED PDF\Compiler_Trial\' , 'HP-*.pdf' , false);
  if( PascalFiles.Text <> '' ) then
       begin
            ShellExecute(0,nil, PChar('cmd'),PChar('/K start C:\HPBossSending.exe /F'),nil,0);
           //ShowMessage('Without!');
       end
  else
      begin
          Delay(5000);
          Goto A;
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
    //Form1.ProgressBar1.Visible:=false;
end;
















end.

