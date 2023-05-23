
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

    $(".checkcfg").click(function () {
        //alert('change');
        $val = $(this).is(':checked') ? 1 : 0;
    $.post("ajax/setconfigiteminifile.php", {item: $(this).prop('id'),val: $val });
    }); //fine $(".check").click


        //GESTIONE TUTTI I RUOLI
        $('.updRuoli').click(function (e) {
            //Aggiorna tabelle RUOLI E SUBRUOLI
            let $idButton = $(e.target).prop('id');
            if ($idButton == 'insRolePrim') { //Inserisce nuovo ruolo primario
                if (!$('#newRolePrim').val()) {
                    alert('Errore: Non \u00E8 stato inserito un nome per il ruolo primario!');
                    $('#newRolePrim').focus();
                    return false;
                }
                var data = { idRl: 1, idRs: 0, val: $('#newRolePrim').val() };
            }
            else if ($idButton == 'addRoleSec') { //Aggiunge un ruolo secondario a primario
                if (!$('#listAllRoles').val()) {
                    alert('Errore: Non \u00E8 stato selezionato alcun ruolo primario!');
                    return false;
                }
                else if ($('#listAllRoles option:selected').attr("class") == 'optionChild') {
                    alert('Errore: \u00C8 stato selezionato un ruolo secondario!\nSelezionare un ruolo primario!');
                    return false;
                }
                else if (!$('#listRolesSec').val()) {
                    alert('Errore: Non \u00E8 stato selezionato il ruolo secondario da assegnare al ruolo "' + $('#listAllRoles option:selected').text() + '"!');
                    return false;
                }
                var data = { idRl: $('#listAllRoles').val(), idRs: $('#listRolesSec').val(), val: '' };
            }
            else if ($idButton == 'insRoleSec') {
                if (!$('#newRoleSec').val()) {
                    alert('Errore: Non \u00E8 stato inserito un nome per il ruolo secondario!');
                    $('#newRoleSec').focus();
                    return false;
                }
                else if (!$('#listAllRoles').val()) {
                    alert('Errore: Non \u00E8 stato selezionato alcun ruolo primario!');
                    return false;
                }
                else if ($('#listAllRoles option:selected').attr("class") == 'optionChild') {
                    alert('Errore: \u00C8 stato selezionato un ruolo secondario!\nSelezionare un ruolo primario!');
                    return false;
                }
                var data = { idRl: $('#listAllRoles').val(), idRs: 1, val: $('#newRoleSec').val() };
            }
            else if ($idButton == 'delRole') {
                if (!$('#listAllRoles').val()) {
                    alert('Errore: Non \u00E8 stato selezionato alcun ruolo da eliminare!');
                    return false;
                }
                else if (!confirm('Eliminare il ruolo "' + $('#listAllRoles option:selected').text() + '"?')) return false;
                var data = { idRl: $('#listAllRoles').val(), idRs: 0, val: '' };
            }
            $.post('adminsez/admin/ajax/updruoli.php', data
            ).done(function (result) {
                if (result.substr(0, 2) === '->') {
                    alert('Errore 000-20: ' + result);
                    logerror('-20', 'setActRuoli.php', result);
                }
                else {//aggiorna contenuto list tutti ruoli e, se caso, list subruoli
                    $("#listAllRoles").load(location.href + " #listAllRoles>*");
                    if ($idButton == 'insRoleSec' || $idButton == 'delRole')
                        $("#listRolesSec").load(location.href + " #listRolesSec>*");
                    /*  $("#RolesUs").load(location.href + " #RolesUs>*");*/

                    $('#newRoleSec,#newRolePrim').val('');
                }
            }).fail(function (xhr, ajaxOptions, thrownError) { //errore ajax
                let str = "Errore 000-21: " + xhr.status + " " + thrownError;
                alert(str);
                logerror('-21', 'setActRuoli.php', str);
            });
        });

    // GESTIONE VALORI CONFIGURATI IN INI FILE
    $("#scPsw").change(function () {
      //  $.post("ajax/setconfigiteminifile.php", {item: $(this).prop('id'),val: $(this).val() });
       if ($("#scPsw").val() == 0) {
        $("#ggMsgPsw").val(0);
    $.post("ajax/setconfigiteminifile.php", {item: 'ggMsgPsw',val: 0 });
    $("#ggMsgPsw").prop("disabled", true);
        } else $("#ggMsgPsw").prop("disabled", false);
    }); //fine $("#scPsw").change

    $('.inputcfg').change(function () {
        $.post("ajax/setconfigiteminifile.php", { item: $(this).prop('id'), val: $(this).val() });
    }); //fine $("input").change

