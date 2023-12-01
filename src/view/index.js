"use strict";

/**
 *
 * @param {string} ownerName
 */
function filterTableByOwner(ownerName){
    let trs = [...document.querySelectorAll("#CallEntryTable > tbody > tr + tr")];


    if (ownerName !== "Alle Anrufe") {
        for (const tr of trs) {
            /**
             * @type {string[]}
             */
            let owners = JSON.parse(tr.getAttribute('owners'));
            let isCorrectOwner = owners.includes(ownerName);
            tr.hidden = !isCorrectOwner;
        }
    } else {
        trs.forEach(tr => tr.hidden = false);
    }

    let spanCallCount = document.querySelector('#callCount');
    spanCallCount.textContent = trs.filter(tr => tr.hidden == false).length;
}

/**
 * @param {HTMLSelectElement} logYearSelectEle
 * @param {boolean} shouldSelectFirst
 */
function showLogsForSelectedYear(logYearSelectEle, shouldSelectFirst=true) {

    let  year = logYearSelectEle.value;
    console.info(`year: ${year}`);
    let logsSelectElement = logYearSelectEle.nextElementSibling;


    for (const opt of logsSelectElement.options) {
        let optYear = opt.getAttribute('year');
        opt.hidden = optYear !== year;
        console.info(`hide status for ${opt.text} => ${opt.hidden}`)
    }

    if (shouldSelectFirst) {
        let firstOptionForYear = [...logsSelectElement.options].filter(opt => opt.getAttribute('year') === year)[0];
        logsSelectElement.selectedIndex = -1;
    }
}

let path = location.origin + location.pathname;

class CallEntry {
    logfileName = "";
    destinationNumber = "";
    sourceNumber = "";
    fileName = "";
    callIP = "";
    callTimeBegin = "";
    callTimeEnd = "";
    callDuration = "";
    callAccepted = false;
    acceptedBranchOffice = "";
    callType = "";
    callFinished = false;


    constructor(jsonObj) {
        this.logfileName = jsonObj.logfileName;
        this.destinationNumber = jsonObj.destinationNumber;
        this.sourceNumber = jsonObj.sourceNumber;
        this.fileName = jsonObj.fileName;
        this.callIP = jsonObj.callIP;
        this.callTimeBegin = jsonObj.callTimeBegin;
        this.callTimeEnd = jsonObj.callTimeEnd;
        this.callDuration = jsonObj.callDuration;
        this.callAccepted = jsonObj.callAccepted;
        this.acceptedBranchOffice = jsonObj.acceptedBranchOffice;
        this.callType = jsonObj.callType;
        this.callFinished = jsonObj.callFinished;
    }
}

function createTableRowFromCallEntry(json) {
    let tableEntries = [];
    for (let callEntryJSON of json) {
        const callEntry = new CallEntry(callEntryJSON);

        const CallTableEntry = `
                        <td height="35" align="center" valign="top" class="text">${callEntry.callType}&nbsp;</td>
                        <td height="35" align="left" valign="top" class="text">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            ${callEntry.sourceNumber == "" ? `
                                    <tr>
                                        <td width="80%" align="left">${callEntry.sourceNumber}</td>
                                        <td width="20%" align="right">
                                            <a style="text-decoration: none" href="telebuch.php?nr=${callEntry.sourceNumber}"
                                                                         title="Telefonbucheintrag hinzufügen">
                                                <span style="color: green; font-weight: bolder; width: 12px; height: 12px; display: inline-block; ">&#x2795;</span>
                                            </a></td>
                                    </tr>
                                    <tr>
                                        <td width="80%" align="left">&nbsp;</td>
                                        <td width="20%" align="right">&nbsp;</td>
                                    </tr>
` : `
                                    <tr>
                                        <td width="80%" align="left">${callEntry.sourceNumber}</td>
                                        <td width="20%" align="right">
                            
                                            <a style="text-decoration: none;" href='telebuch_edit.php?nr=${callEntry.sourceNumber} &_name=${callEntry.sourceNumber}'
                                               title="Telefonbucheintrag ändern">
                                                <span style="color: royalblue">&#x270E;</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="80%" align="left">${callEntry.sourceNumber}</td>
                                        <td width="20%" align="right">
                                            <a style="text-decoration: none" href="telebuch_del.php?nr=${callEntry.sourceNumber} &_name= ${callEntry.sourceNumber}"
                                               title="Telefonbucheintrag löschen">
                                                <span style="color: #FF0000">&#10007;</span>
                                            </a>
                                        </td>
                                    </tr>
`}
                            </table>
                        </td>
                        <td height="35" align="center" valign="top" class="text">${callEntry.acceptedBranchOffice}</td>
                        <td height="35" align="center" valign="top" class="text">${callEntry.callIP}</td>
                        <td height="35" align="left" valign="top" class="text">${callEntry.destinationNumber}</td>
                        <td height="35" align="center" valign="top" class="text">${callEntry.callTimeBegin}</td>
                        <td height="35" align="center" valign="top" class="text">${callEntry.callDuration}</td>
`;
        tableEntries.push(CallTableEntry);
    }

    return tableEntries;
}

function getLogfileFromServer( event ) {
    let req = new XMLHttpRequest();
    req.open("GET", path + "?logfile=" + event.value, true);

    req.onload = function () {
        let tableRowFromCallEntry = createTableRowFromCallEntry(JSON.parse(req.response));
        for (let tableRow of tableRowFromCallEntry) {
            let htmlTableRowElement = document.createElement("tr");
            htmlTableRowElement.innerHTML = tableRow;
            document.querySelector("#CallEntryTable tbody").append(htmlTableRowElement);
        }
    }
    req.send();
}