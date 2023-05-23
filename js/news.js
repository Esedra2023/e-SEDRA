
/** This file is part of e-Sedra.
 *
 *   e-Sedra is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   e-Sedra is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *   along with e-Sedra.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright 2023 e-Sedra. All Rights Reserved.
 *
 */
//var urlROOT;
$(document).ready(function () {
	//call_ajax_rootpath();
	
	nascondiScadute();
	ck = document.getElementById("newscadute");
	if (ck != null)
		ck.addEventListener("click", function () { mostraNews(this.checked); });
});

function nascondiScadute() {
	let acc = document.getElementById("NewsScadAccHP");	
	acc.classList.add('d-none');
}


function mostraNews(ck) {
	
	if (ck) { 
		mostraScadute();
	}
	else
		nascondiScadute();
}

function mostraScadute() {
	let acc = document.getElementById("NewsScadAccHP");
	acc.classList.remove('d-none');
}
	






