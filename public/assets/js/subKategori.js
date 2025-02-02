function addLevel1() {
    addLevel(1);
    return;
}

function addLevel2(event) {
    addLevel(2, event);
}

function addLevel3(event) {
    addLevel(3, event);
    return;
}
function addLevel(level, event = undefined) {
    let childLength;
    if (level == 1) childLength = $(".group-level1").children().length;
    if (level == 2)
        childLength = $(event)
            .parent()
            .parent()
            .parent()
            .find(".level-2")
            .children().length;
    if (level == 3)
        childLength = $(event)
            .parent()
            .parent()
            .parent()
            .find(".level-3")
            .children().length;

    const label = `<label for=''>Level ${level}</label>`;
    let urutanParent = $(event)
        .parent()
        .parent()
        .find("input")
        .attr("data-index");
    let urutanParent2 = $(event)
        .parent()
        .parent()
        .find("input")
        .attr("data-index");
    let urutanParent1 = $(event)
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .find(".level1")
        .attr("data-index");
    const nameLv1 = `level_1[]`;
    const nameLv2 = `parent_${urutanParent}|level_2[]`;
    const nameLv3 = `parent_${urutanParent1}|parent_${urutanParent2}|level_3[]`;
    let name = level == 1 ? nameLv1 : level == 2 ? nameLv2 : nameLv3;
    const levelEvent =
        level == 1 ? "addLevel2(this)" : level == 2 ? "addLevel3(this)" : "";
    let btn =
        level != 3
            ? `<button data-bs-placement="top" data-bs-toggle="tooltip"
            title="Level ${
                level + 1
            }" type="button" class="btn btn-primary" onclick="${levelEvent}"><i class="fa fa-plus"></i>  Level ${
                  level + 1
              }</button>`
            : "";
    var url = window.location.href;
    var parts = url.split("/");
    const kategori = parts[4];
    const latLong =
        kategori == "2"
            ? `<input type='text' placeholder='Masukkan Latitude' name='lat' class='form-control scope-area-location'> <input type='text' name='long' placeholder='Masukkan Longitude' class='form-control scope-area-location'>`
            : "";
    const mapBtn =
        kategori == "2"
            ? `<button type="button" class="btn btn-primary btn-map" onclick='setMapPin(this)'  
                        href="#modal-map"><i class="fa fa-map"></i></button>`
            : "";
    console.log(latLong, kategori);
    let child = $(`
            <div class=" ${
                level == 1 ? "level-1 border p-4" : ""
            } group-level${level}-${childLength + 1}  mb-2">
                ${label}
                                       <div class="row mb-2">
                                    <div class="col-md-9 col-12 d-flex gap-2">
                                        <input onkeyup="updateLabel(this)" data-index="${childLength}" required type="text" class="form-control level${level}"
                                        name="${name}"
                                            id="">
                                            ${latLong}
                                    </div>
                                    <div class="col-md-3 d-flex gap-2">
                                    ${mapBtn}
                                        ${btn}
                                        <button onclick="removeElement(this)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </div>
                                    </div>
                                    <div class="level-${level + 1} ms-3"></div>

                            </div>`);
    if (level == 1) $(".group-level1").append(child);
    if (level == 2)
        $(event).parent().parent().parent().find(".level-2").append(child);
    if (level == 3)
        $(event).parent().parent().parent().find(".level-3").append(child);

    //untuk mengahpus lotlang jika memiliki child
    let scopeAreaLocations = $(".scope-area-location");
    scopeAreaLocations.each(function (index, area) {
        const el = $(area).parent().parent().parent();
        const mapBtn = $(area).parent().parent().find('[href="#modal-map"]');
        const count = el.find(".scope-area-location").length;
        if (count > 2) {
            $(area).remove();
            $(mapBtn).remove();
        }
    });

    $('[data-bs-toggle="tooltip"]').tooltip();
}

function removeElement(e) {
    const parent = $(e).parent().parent().parent().parent();
    $(e).parent().parent().parent().remove();
    let children1 = $(".group-level1").children();
    var url = window.location.href;
    var parts = url.split("/");
    const kategori = parts[4];
    if (parent.children().length == 0) {
        //untuk mengambil input dan btn
        const firstInput = parent.parent().find("input").first();
        const firstBtn = parent.parent().find("button").first();
        const inputName = firstInput.val().replace(/\s+/g, "-");
        const mapBtn =
            kategori == "2"
                ? `<button type="button" class="btn btn-primary btn-map" onclick='setMapPin(this)'  data-bs-effect="effect-scale" data-bs-toggle="modal"
                        href="#modal-map"><i class="fa fa-map"></i></button>`
                : "";
        const latLong =
            kategori == "2"
                ? `<input type='text'  placeholder='Masukkan Latitude' name='lat-${inputName}' class='form-control scope-area-location'> <input type='text'  name='long-${inputName}' placeholder='Masukkan Longitude' class='form-control scope-area-location'>`
                : "";
        firstInput.after(latLong);
        firstBtn.before(mapBtn);
    }
    $(".group-level1")
        .children()
        .each(function (i) {
            $(this)
                .find("input[type='text']:not(.scope-area-location)")
                .attr("data-index", i);

            $(this)
                .find(".level-2")
                .children()
                .each(function (j) {
                    $(this)
                        .find("input[type='text']:not(.scope-area-location)")
                        .attr("data-index", j);
                    $(this)
                        .find("input[type='text']:not(.scope-area-location)")
                        .attr("name", "parent_" + i + "|level_2[]");

                    $(this)
                        .find(".level-3")
                        .children()
                        .each(function (h) {
                            $(this)
                                .find(
                                    "input[type='text']:not(.scope-area-location)"
                                )
                                .attr("data-index", h);
                            $(this)
                                .find(
                                    "input[type='text']:not(.scope-area-location)"
                                )
                                .attr(
                                    "name",
                                    "parent_" +
                                        i +
                                        "|parent_" +
                                        j +
                                        "|level_3[]"
                                );
                        });
                });
        });
}
