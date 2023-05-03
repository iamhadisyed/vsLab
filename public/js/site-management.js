/**
 * Created by James on 3/18/2019.
 */

function modal_site_create(element) {
    var modal = $('#modal-site-create');
    // var select_site = $('#site_select');

    // $.getJSON('/siteadmin/getSiteAll', function(sites) {
    //     $.each(sites, function(index, site) {
    //         select_site.append($('<option value="' + site.id + '" data-desc="' + site.description + '" >' +  site.name.trim() + '</option>'));
    //     });
    // });

    modal.modal('show');
}

function site_create(element) {
    var name = $('#new-site-name').val().trim();
    var admins = $('#new-site-admins').val().trim();
    var desc = $('#new-site-description').val().trim();
    if (name.length <= 0|| desc.length <=0) {
        Swal.fire('Please enter Site Name and Description!', '', 'warning');
        return;
    }
    if (admins.indexOf(' ') !== -1) {
        Swal.fire('Please enter correct email addresses!', '', 'error');
        return;
    }
    var require_rss = {};

    //var expiration = $('#expiration').val();
    var labs = parseInt($('#labs').val());
    var vms = parseInt($('#vms').val());
    var vcpus = parseInt($('#vcpus').val());
    var ram = parseInt($('#ram').val());
    var storage = parseInt($('#storage').val());

    if  (isNaN(labs) || isNaN(vms) || isNaN(vcpus) || isNaN(ram) || isNaN(storage)) {
        Swal.fire('Please enter integer in the resource fields!', '', 'warning');
        return;
    }
    // if (expiration.match(/^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/) === null) {
    //     Swal.fire('The date format is incorrect!', '', 'error');
    //     return;
    // }

    require_rss['labs'] = labs;
    require_rss['vms'] = vms;
    require_rss['vcpus'] = vcpus;
    require_rss['ram'] = ram;
    require_rss['storage'] = storage;
    //require_rss['expiration'] = expiration;

    var site = {name: name, desc: desc, //expiration: expiration,
        resources: JSON.stringify(require_rss)};

    $.post("sites", {
            "site": site,
            "admins": admins
        }, function (result) {
            $('#modal-site-create').modal('hide');

            if (result.status === 'Success') {
                Swal.fire(result.message, '', 'success');
                $('#dataTableBuilder').DataTable().draw();
            }
            else {
                Swal.fire('Create Site Failed!', result.message, 'error');
            }
        },
        'json'
    );
}

function modal_site_edit(element) {
    var data = $('#dataTableBuilder').DataTable().row(element.closest('tr')).data();
    var siteId = data.id;
    var siteName = data.name;
    var siteDesc = data.description;
    var siteAdmins = data.admins.replace('<br>', ';');
    var rss = JSON.parse(data.resources.replace(/&quot;/g,'"'));

    var modal = $('#modal-site-edit');
    modal.modal('show');

    modal.find('#site-id-edit').empty().html(siteId);
    modal.find('#site-name-edit').empty().html(siteName);
    modal.find('#site-edit-name').empty().val(siteName);
    modal.find('#site-edit-description').empty().val(siteDesc);
    modal.find('#site-edit-admins').empty().val(siteAdmins);
    modal.find('#site-edit-labs').empty().val(rss.labs);
    modal.find('#site-edit-vms').empty().val(rss.vms);
    modal.find('#site-edit-vcpus').empty().val(rss.vcpus);
    modal.find('#site-edit-ram').empty().val(rss.ram);
    modal.find('#site-edit-storage').empty().val(rss.storage);
}

function site_edit(element) {
    var modal = $('#modal-site-edit');
    var siteId = modal.find('#site-id-edit').html().trim();
    var name = modal.find('#site-edit-name').val().trim();
    var admins = modal.find('#site-edit-admins').val().trim();
    var desc = modal.find('#site-edit-description').val().trim();

    if (name.length <= 0|| desc.length <=0) {
        Swal.fire('Please enter Site Name and Description!', '', 'warning');
        return;
    }
    if (admins.indexOf(' ') !== -1) {
        Swal.fire('Please enter correct email addresses!', '', 'error');
        return;
    }
    var require_rss = {};

    //var expiration = $('#expiration').val();
    var labs = parseInt($('#site-edit-labs').val());
    var vms = parseInt($('#site-edit-vms').val());
    var vcpus = parseInt($('#site-edit-vcpus').val());
    var ram = parseInt($('#site-edit-ram').val());
    var storage = parseInt($('#site-edit-storage').val());

    if  (isNaN(labs) || isNaN(vms) || isNaN(vcpus) || isNaN(ram) || isNaN(storage)) {
        Swal.fire('Please enter integer in the resource fields!', '', 'warning');
        return;
    }
    // if (expiration.match(/^\d{1,2}[\/-]\d{1,2}[\/-]\d{4}$/) === null) {
    //     Swal.fire('The date format is incorrect!', '', 'error');
    //     return;
    // }

    require_rss['labs'] = labs;
    require_rss['vms'] = vms;
    require_rss['vcpus'] = vcpus;
    require_rss['ram'] = ram;
    require_rss['storage'] = storage;
    //require_rss['expiration'] = expiration;

    var site = {name: name, description: desc, //expiration: expiration,
        resources: JSON.stringify(require_rss)};

    $.post("sites/update", {
            "id": siteId,
            "site": site,
            "admins": admins
        }, function (result) {
            modal.modal('hide');

            if (result.status === 'Success') {
                Swal.fire(result.message, '', 'success');
                $('#dataTableBuilder').DataTable().draw(false);
            }
            else {
                Swal.fire('Site Update Failed!', result.message, 'error');
            }
        },
        'json'
    );
}

function site_delete(element) {

}