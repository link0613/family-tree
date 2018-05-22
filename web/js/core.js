// Graph replacement.
var treeData = [];

/**
 * @param {Array} treeData
 */
function initDTreeSVG(treeData) {
    // Get chart dimensions
    var chart = document.getElementById('chart');
    var chartWidth = chart.offsetWidth;

    var windowInnerHeight = window.innerHeight;
    var topNavbarHeight = $('#topNavbar').height();
    var bottomFooterHeight = 116;

    chartHeight = windowInnerHeight - 30 - topNavbarHeight - bottomFooterHeight - 22;

    chartHeight += Math.ceil(chartHeight / 3);

    $('#mainArea').height(chartHeight);

    // Build dTree graph
    dTree.init(treeData, {
        target: "#graph",
        debug: true,
        height: chartHeight,
        width: chartWidth,
        nodeWidth: 200,
        callbacks: {
            nodeClick: function (name, extra, id, gender) {
                fillNodeTypeSelect('#node_type_select');

                var a = "/view";
                var profile = $("#profile");
                profile.find("#f_name").html(name);
                profile.find("#l_name").html(extra.last_name);
                profile.find("#birth").html(extra.birth);
                profile.find("#gender").html(gender);
                profile.find("#genderTr").hide();

                profile.find("#deathTr").hide();
                if (extra.death != null) {
                    profile.find("#death").html(extra.death);
                    profile.find("#deathTr").show();
                }

                profile.find("#image").attr("src", extra.image);
                profile.find("#view").attr("href", a + "/" + id);

                $('#person_name').html(name);

                $('#parentId').val(id);

                //  hide brother & sister if parents are not set
                if (hasPersonParents[id]['father'] == null && hasPersonParents[id]['mother'] == null) {
                    $('.node_type_4').prop( "disabled", true );//hide();
                    $('.node_type_3').prop( "disabled", true );//hide();
                } else {
                    $('.node_type_4').prop( "disabled", false );//show();
                    $('.node_type_3').prop( "disabled", false );//show();
                }

                //  hide mother if exists
                if (hasPersonParents[id]['mother'] !== null) {
                    $('.node_type_1').prop( "disabled", true );//hide();
                } else {
                    $('.node_type_1').prop( "disabled", false );//show();
                }

                //  hide father if exists
                if (hasPersonParents[id]['father'] !== null) {
                    $('.node_type_2').prop( "disabled", true );//hide();
                } else {
                    $('.node_type_2').prop( "disabled", false );//show();
                }
            }
        }
    });

    //workaround for making dtree take full height
    $('.block.noPadVert').css("height", chartHeight + "px");
    $('#chart').css("height", chartHeight + "px");

    $('.get-oc-c').css("height", "100%");

    return getMappedData(treeData, dTree.flatNodes);
}

/**
 * Replaces getOrgChart SVG within dTree implementation.
 *
 */
function replaceSVG() {
    // Get chart dimensions
    var chart = document.getElementById('chart');
    var chartWidth = chart.offsetWidth;
    var chartHeight = chart.offsetHeight;

    // Detect existing SVG's on the page.
    var svgElements = document.getElementsByTagName('svg');

    var currentSvg = svgElements[0];
    var dTreeSvg = svgElements[1];

    var viewBox = [
        ((window.GLOBAL_OFFSET || 600) * 1),          // min-x
        0,          // min-y
        chartWidth, // width
        chartHeight // height
    ];

    // Create a viewBox string, like: 30,120,470,290
    currentSvg.setAttribute('viewBox', viewBox.join());

    if( currentSvg.children ) {
        var g = currentSvg.children[1];
        var g2 = dTreeSvg.children[0];

        // Remove transform="translate(600,0)"
        //g2.setAttribute('transform', '');

        currentSvg.replaceChild(g2, g);
    } else if( currentSvg.childNodes ) {
        var g = currentSvg.childNodes[1];
        var g2 = dTreeSvg.childNodes[0];

        // Remove transform="translate(600,0)"
        //g2.setAttribute('transform', '');

        currentSvg.replaceChild(g2, g);
    }

    // Clean up temporary graph
    var tmpGraph = document.getElementById('graph');
    tmpGraph.parentElement.removeChild(tmpGraph);
}

/**
 *
 *
 * @param {Array} treeData
 * @param {Array} flatNodes
 */
function getMappedData(treeData, flatNodes) {
    var result = [];

    if( flatNodes ) {
        flatNodes.forEach(function (item, i, arr) {
            // Add width and height as item w and h keys.
            item.w = item.cWidth;
            item.h = item.cHeight;
            result[item.id] = item;
        });
    }

    return result;
}

function hidePop(el) {
    var popover = $(el).closest('div.popover');
    popover.popover('hide');
    popover.popover('destroy');
}
function setPops() {
    var closeBtn = '<button style="margin: 10px" type="button" onclick="hidePop(this)" class="btn pull-right btn-theme btn-sm">Got it</button><br>';
    var closeIcon = '<i class="fa pull-right fa-times" onclick="hidePop(this)"></i>';
    var targets = {
        0: '#add',
        1: '#view',
        2: '.toggleArea',
        3: '#chartSearch'
    };
    var content = {
        0: {
            title: 'Add person.' + closeIcon,
            content: 'Use this button to add person for selected diagram card. To add new person you need to click this button and ' +
            'set required information about new person in popup which will appear after clicking the button. ' + closeBtn,
            placement: 'top'
        },
        1: {
            title: 'View profile.' + closeIcon,
            content: 'Use this button to view profile of the selected person from diagram.' + closeBtn,
            placement: 'top'
        },
        2: {
            title: 'Extend diagram area.' + closeIcon,
            content: 'Use this button to extend diagram area if needed. Second click will set default position.' + closeBtn,
            placement: 'left'
        },
        3: {
            title: 'Tree diagram search.' + closeIcon,
            content: 'Use this field to search persons in the diagram. Just type search value and press Enter.' + closeBtn,
            placement: 'right'
        }
    };

    var options = {
        html: true,
        trigger: 'manual'
    };

    options = $.extend(options, content[0]);
    $(targets[0]).popover(options);
    $(targets[0]).popover('show');
    $(targets[0]).on('hidden.bs.popover', function () {
        options = $.extend(options, content[1]);
        $(targets[1]).popover(options);
        $(targets[1]).popover('show');
        $(targets[1]).on('hidden.bs.popover', function () {
            options = $.extend(options, content[2]);
            $(targets[2]).popover(options);
            $(targets[2]).popover('show');
            $(targets[2]).on('hidden.bs.popover', function () {
                options = $.extend(options, content[3]);
                $('.get-oc-tb, .get-org-chart').css('overflow', 'visible');
                $('.get-oc-tb > div ').css('overflow', 'visible');
                $(targets[3]).popover(options);
                $(targets[3]).popover('show');
                $(targets[3]).on('hidden.bs.popover', function () {
                    $(' .get-oc-tb, .get-org-chart').css('overflow', 'hidden');
                    $(' .get-oc-tb > div ').css('overflow', 'hidden');
                });
            });
        });
    });
}

var orgChart;

/**
 * Output family graph.
 *
 * @param {Array} tree
 * @param {Number} popover
 */
function printTree(tree, popover) {
    var btnAdd = '';
    var btnEdit = '';

    getOrgChart.themes.monica.box += '<g transform="matrix(1,0,0,1,350,10)">'
        + btnAdd
        + btnEdit
        + '</g>';
    var peopleElement = document.getElementById("chart");

    orgChart = new getOrgChart(peopleElement, {
        primaryFields: ['full_name', 'birth'],
        theme: "monica",
        expandToLevel: 8,
        subtreeSeparation: 200,
        siblingSeparation: 200,
        levelSeparation: 250,
        linkType: "B",
        editable: false,
        enablePrint: false,
        orientation: getOrgChart.RO_BOTTOM,
        photoFields: ["image"],
        enableEdit: false,
        enableDetailsView: false,
        dataSource: [{}],
        dTreeDataSource: tree
    });

    function getNodeByClickedBtn(el) {
        while (el.parentNode) {
            el = el.parentNode;
            if (el.getAttribute("data-node-id"))
                return el;
        }
        return null;
    }

    var init = function () {
        var btns = document.getElementsByClassName("btn");
        for (var i = 0; i < btns.length; i++) {

            btns[i].addEventListener("click", function () {
                var nodeElement = getNodeByClickedBtn(this);
                var action = this.getAttribute("data-action");
                var id = nodeElement.getAttribute("data-node-id");
                var node = orgChart.nodes[id];

                switch (action) {
                    case "add":
                        $('#nodeType').modal('show');
                        break;
                    case "edit":
                        document.getElementById("hdnId").value = node.id;
                        document.getElementById("txtName").value = node.data.name ? node.data.name : "";
                        document.getElementById("txtTitle").value = node.data.title ? node.data.title : "";
                        document.getElementById("txtMail").value = node.data.mail ? node.data.mail : "";
                        $("#dialog").dialog("open");
                        break;
                    case "delete":
                        orgChart.removeNode(id);
                        break;
                }
            });
        }
    };

    if (popover) {
        setPops();
    }
}

function sendEmail() {
    $("#inviteEmail").val($("#emailList option:selected").data("email"))
}

function toggleArea() {

    var profileArea = $('#profileArea');
    var chartArea = $('#chartArea');
    if (chartArea.hasClass('on')) {
        chartArea.animate({
            'width': '75%'
        }, function () {
            profileArea.show();
        });
    } else {
        profileArea.hide();
        chartArea.animate({
            'width': '100%'
        });
    }
    chartArea.toggleClass('on');
}

function deathToggle(e) {
    e.innerHTML = 0 === e.innerHTML.localeCompare("Dead") ? "Alive" : "Dead";
    var a = parseInt($("#isDead").val());
    1 == a ? $("#isDead").val(0) : $("#isDead").val(1)
}

function privacyToggle(e) {
    e.innerHTML = 0 === e.innerHTML.trim().localeCompare("Public") ? "Private" : "Public";
    var a = parseInt($("#privacy").val());
    1 == a ? $("#privacy").val(0) : $("#privacy").val(1)
}

function faColorLeave(e) {
    var a = $($(e).data("target"));
    a.hasClass("active") || a.css("color", "inherit")
}

function faColorOver(e) {
    $($(e).data("target")).css("color", "#91DC5A")
}

function shakeRegForm() {
    setTimeout(function () {
        $(".regForm").animateCss("animated tada")
    }, 250)
}

function defineGender() {
    0 == $("#item-gender").val() ? $(".field-maidenInput").css("display", "block") : $(".field-maidenInput").css("display", "none")
}

function genderChange(e) {
    0 == e.value ? ($(".field-maidenInput").css("display", "block"), $(".field-maidenInput").animateCss("animated fadeInLeft")) : $(".field-maidenInput").css("display", "none")
}

function activeField(field) {
    if (field.val().length > 0) {
        field.css("border-bottom", "2px solid #cbfba9");
    }
    else {
        field.css("border-bottom", "2px solid #ddd");
    }
}

//toogle gender
function setGenderForm(gender) {
    $('#gender_val span').removeClass('active');
    $('#gender_val span:eq(' + gender + ')').addClass('active');
    $('#genderInput').val(gender);
}

//ability to change gender
function canSetGender(element) {
    var parent = $(element).parent();
    var clicked = $(element);
    if (parent.hasClass('changeable')) {
        setGenderForm(clicked.data('gender'));
    }
}

// set default options to node_type_select dropdown
function fillNodeTypeSelect(selectId) {
    var html = '<option class="node_type_0" value="">--- Select ---</option>' +
        '<option class="node_type_1" value="1">Mother</option>' +
        '<option class="node_type_2" value="2">Father</option>' +
        '<option class="node_type_3" value="3">Sister</option>' +
        '<option class="node_type_4" value="4">Brother</option>' +
        '<option class="node_type_5" value="5">Daughter</option>' +
        '<option class="node_type_6" value="6">Son</option>' +
        '<option class="node_type_7" value="7">Spouse/Partner</option>';
    $(selectId).html(html);
}

function setReturnTreeId() {
    $("#returnToTree").val(1);
    return true;
}

function setAddNewNode() {
    $("#addNewNode").val(1);
    return true;
}

$(document).ready(function () {

    $('#node_type_select').change(function () {

        var id = $('#parentId').val();
        var node_type = parseInt($(this).val());
        var gender_val = $('#gender_val');
        gender_val.removeClass('changeable');

        //  hide all nodes
        $('#marriageDiv').hide();
        $('#siblingsDiv').hide();
        $('#partnerChildren').hide();

        var selectMarriageType = $("#marriage_type");
        selectMarriageType.empty();
        selectMarriageType.hide();
        $('#marriageChild').prop('checked', false);

        var selectSiblingsType = $("#siblings_type");
        selectSiblingsType.empty();
        selectSiblingsType.hide();
        $('#siblingChild').prop('checked', false);

        //  Daughter / son
        if ((node_type == 5 || node_type == 6)) {
            $.ajax({
                method: "GET",
                url: "/profile/marriages",
                data: {id: id}
            }).success(function (data) {
                var items = JSON.parse(data);
                if (items.length > 0) {
                    $.each(items, function () {
                        selectMarriageType.append($("<option />").val(this[0]).text("Marriage with " + this[1]));
                    });
                    $("#marriageDiv").show();
                    $('#marriageChild').prop('checked', true);
                    $("#marriage_type").show();
                }
            });
        }
        //  sister / brother
        else if ((node_type == 3 || node_type == 4) &&
            (hasPersonParents[id]['father'] != null || hasPersonParents[id]['mother'] != null)) {
            $.each(hasPersonParents[id], function () {
                if (this['id'] !== undefined && this['name'] !== undefined) {
                    selectSiblingsType.append($("<option />").val(this['id']).text(this['name']));
                }
            });
            $("#siblingsDiv").show();
        }
        else {
            $("#marriageDiv").hide();
            $("#siblingsDiv").hide();
            $("#partnerChildren").hide();
        }

        if (node_type == 7) {
            // set gender
            if (treeNodes[id] !== undefined) {
                var selectedPerson = treeNodes[id];
                if (selectedPerson.gender == 1) {
                    setGenderForm(0);
                } else {
                    setGenderForm(1);
                }
            }

            // show person children
            $.ajax({
                method: "GET",
                url: "/profile/children",
                data: {id: id}
            }).success(function (data) {
                var items = JSON.parse(data);
                if (items.length > 0) {
                    $.each(items, function () {
                        var childItem = '<input name="Additional[partner_children][]" type="checkbox" value="' + this['id'] + '">'
                            + this['fullName'] + '<br />';
                        $('#partnerChildrenContainer').append(childItem);
                    });
                    $('#partnerChildren').show();
                }
            });
        } else if (node_type % 2 == 0) {
            setGenderForm(1);
        } else {
            setGenderForm(0);
        }
    });

    $('#marriageChild').change(function () {
        if ($('#marriageChild').is(":checked")) {
            $("#marriage_type").show();
        } else {
            $("#marriage_type").hide();
        }
    });

    $('#siblingChild').change(function () {
        if ($('#siblingChild').is(":checked")) {
            $("#siblings_type").show();
        } else {
            $("#siblings_type").hide();
        }
    });

    $('#nodeType').on('hide.bs.modal', function (e) {
        $("#marriage_type").hide();
        $("#marriageDiv").hide();
        $('#marriageChild').prop('checked', false);
    });

    $('form select').change(function () {
        activeField($(this));
    });

    $('form input').change(function () {
        activeField($(this));
    });

    $.fn.extend({
        animateCss: function (e) {
            var a = "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
            this.addClass("animated " + e).one(a, function () {
                $(this).removeClass("animated " + e)
            })
        }
    }), setTimeout(function () {
        $(".alert-info").animate({opacity: 0, height: 0, padding: 0, margin: 0}, 1e3)
    }, 1e3), $("a.page-scroll").bind("click", function (e) {
        var a = $(this);
        $("html, body").stop().animate({scrollTop: $(a.attr("href")).offset().top}, 1500, "easeInOutExpo"), e.preventDefault()
    }), $(window).scroll(function () {
        $(this).scrollTop() > 0 ? $("#scroller").fadeIn() : $("#scroller").fadeOut()
    }), $("#scroller").click(function () {
        $("body,html").animate({scrollTop: 0}, 400)
    }), $(".btn").on("click", function () {
        $(this).blur()
    }), $("#searchForm").on("submit", function () {
        var e = !1;
        if ($(this).find("input").each(function () {
                0 != $(this).val().length && (e = !0)
            }), !e) {
            var a = $("#searchError");
            return a.css("opacity", 1), setTimeout(function () {
                a.animate({opacity: 0}, 2e3)
            }, 1500), !1
        }
    }), $("[data-toggle='tooltip']").tooltip(), setTimeout(function () {
        $("#logo").animate({opacity: 1}, 1e3), $("#logo").animateCss("animated fadeInLeft")
    }), $(" input.iCheck").each(function () {
        var e = $(this), a = e.next(), i = a.text();
        a.remove(), e.iCheck({
            radioClass: "iradio_line-green",
            insert: '<div class="icheck_line-icon"></div>' + i
        })
    });

    $('#createParentId').change(function () {
        fillNodeTypeSelect('#createNodeTypeSelect');
        addNewNodeHandler();
    });

    $('#createNodeTypeSelect').change(function () {
        addNewNodeHandler();
    });

    $('#createMarriageChild').change(function () {
        if ($('#createMarriageChild').is(":checked")) {
            $("#createMarriageType").show();
        } else {
            $("#createMarriageType").hide();
        }
    });

    $('#createSiblingChild').change(function () {
        if ($('#createSiblingChild').is(":checked")) {
            $("#createSiblingsType").show();
        } else {
            $("#createSiblingsType").hide();
        }
    });
});

function addNewNodeHandler() {

    // scripts for create person page
    var createParentId = $('#createParentId').val();
    var createNodeTypeSelect = parseInt($('#createNodeTypeSelect').val());

    //  hide
    $("#createMarriageDiv").hide();
    $('#createMarriageChild').prop('checked', false);
    $("#createMarriageType").hide();

    $("#createSiblingsDiv").hide();
    $("#createSiblingChild").prop('checked', false);
    $("#createSiblingsType").html('');

    $('#createPartnerChildren').hide();
    $('#createPartnerChildrenContainer').html('');

    if (userItemsData !== undefined && userItemsData[createParentId] !== undefined) {

        // check if mother/father already exists
        var motherId = userItemsData[createParentId].mother_id;
        if (motherId) {
            $('#createNodeTypeSelect option[value="1"]').remove();
        }

        var fatherId = userItemsData[createParentId].father_id;
        if (fatherId) {
            $('#createNodeTypeSelect option[value="2"]').remove();
        }

        // remove brother/sister is node has no parents
        if (!motherId && !fatherId) {
            $('#createNodeTypeSelect option[value="3"]').remove();
            $('#createNodeTypeSelect option[value="4"]').remove();
        }

        //  add son/daughter
        if (createNodeTypeSelect == 5 || createNodeTypeSelect == 6) {
            var selectMarriageType = $("#createMarriageType");
            selectMarriageType.html('');
            $.ajax({
                method: "GET",
                url: "/profile/marriages",
                data: {id: createParentId}
            }).success(function (data) {
                var items = JSON.parse(data);
                if (items.length > 0) {
                    $.each(items, function () {
                        selectMarriageType.append($("<option />").val(this[0]).text("Marriage with " + this[1]));
                    });
                    $("#createMarriageDiv").show();
                    $('#createMarriageChild').prop('checked', true);
                    $("#createMarriageType").show();
                }
            });
        }

        // brother / sister
        if (createNodeTypeSelect == 3 || createNodeTypeSelect == 4 && (motherId || fatherId)) {
            var selectSiblingsType = $("#createSiblingsType");
            // show mother
            if (userItemsData[motherId] !== undefined) {
                var dataMother = userItemsData[motherId];
                selectSiblingsType.append($("<option />").val(dataMother.id).text(dataMother.first_name));
            }

            // show father
            if (userItemsData[fatherId] !== undefined) {
                var dataFather = userItemsData[fatherId];
                selectSiblingsType.append($("<option />").val(dataFather.id).text(dataFather.first_name));
            }

            $("#createSiblingsDiv").show();
            $("#createSiblingsType").hide();
        }
    }

    if (createNodeTypeSelect == 7) {
        // set gender
        if (userItemsData[createParentId] !== undefined) {
            var selectedPerson = userItemsData[createParentId];
            if (selectedPerson.gender == 1) {
                $('#newPersonGender').val(0);
            } else {
                $('#newPersonGender').val(1);
            }

            // show person children
            $.ajax({
                method: "GET",
                url: "/profile/children",
                data: {id: selectedPerson.id}
            }).success(function (data) {
                var items = JSON.parse(data);
                if (items.length > 0) {
                    $.each(items, function () {
                        var childItem = '<input name="Additional[partner_children][]" type="checkbox" value="' + this['id'] + '">'
                            + this['fullName'] + '<br />';
                        $('#createPartnerChildrenContainer').append(childItem);
                    });
                    $('#createPartnerChildren').show();
                }
            });
        }
    } else if (createNodeTypeSelect % 2 == 0) {
        $('#newPersonGender').val(1);
    } else {
        $('#newPersonGender').val(0);
    }


}

/*
 * Shows casts depends on selected
 */
$('#casts-list').change(function () {
    var caste = $(this).val();
    window.location.replace('/gotra?id=' + encodeURIComponent(caste));
});

$('.add-entry-father').click(function() {
    $('#node_type_select').val('2').trigger('change');
    $('.modal-add-entry').show();
    $('.add-entry-node').hide();
});

$('.add-entry-mother').click(function() {
    $('#node_type_select').val('1').trigger('change');
    $('.modal-add-entry').show();
    $('.add-entry-node').hide();
});

$('.add-entry-sister').click(function() {
    $('#node_type_select').val('3').trigger('change');
    $('.modal-add-entry').show();
    $('.add-entry-node').hide();
});

$('.add-entry-brother').click(function() {
    $('#node_type_select').val('4').trigger('change');
    $('.modal-add-entry').show();
    $('.add-entry-node').hide();
});

$('.add-entry-son').click(function() {
    $('#node_type_select').val('6').trigger('change');
    $('.modal-add-entry').show();
    $('.add-entry-node').hide();
});

$('.add-entry-daughter').click(function() {
    $('#node_type_select').val('5').trigger('change');
    $('.modal-add-entry').show();
    $('.add-entry-node').hide();
});

$('.add-entry-partner').click(function() {
    $('#node_type_select').val('7').trigger('change');
    $('.modal-add-entry').show();
    $('.add-entry-node').hide();
});

        
$('.add-entry-me').click(function() {
    document.getElementById('view').click()
});

$('#add').click(function() {
    setTimeout(function(){
        if ($('.node_type_1').prop('disabled')) {
            $('.add-entry-mother').hide();
            $('#curve1').hide();
        } else {
            $('.add-entry-mother').show();
            $('#curve1').show();
        }
        if ($('.node_type_2').prop('disabled')) {
            $('.add-entry-father').hide();
            $('#curve2').hide();
        } else {
            $('.add-entry-father').show();
            $('#curve2').show();
        }     
        if ($('.node_type_3').prop('disabled')) {
            $('.add-entry-sister').hide();
            $('#curve3').hide();
        } else {
            $('.add-entry-sister').show();
            $('#curve3').show();
        }
        if ($('.node_type_4').prop('disabled')) {
            $('.add-entry-brother').hide();
            $('#curve4').hide();
        } else {
            $('.add-entry-brother').show();
            $('#curve4').show();
        }
        $('.add-entry-center-image').attr('src',$('#profile #image').attr('src'));
        $('.add-entry-fname').html($('#profile #f_name').text());
        $('.add-entry-lname' ).html($('#profile #l_name').text());
        $('.add-entry-birth').html($('#profile #birth').text());
        $('.add-entry-partner').removeClass('add-entry-woman');
        $('.add-entry-partner').removeClass('add-entry-man');
        $('.add-entry-partner').addClass($('#profile #gender').text()==='man'?'add-entry-woman':'add-entry-man');
        $('.add-entry-partner .add-entry-image').attr('src', $('#profile #gender').text()==='man'?'/images/woman.jpg':'/images/man.jpg');

    }, 0);
    try {
        document.getElementById('stay_treeview').value = 0;
    } catch (err){    
    }

    $('.modal-add-entry').hide();
    $('.add-entry-node').show();
});

function goAndStay(){
    document.getElementById('stay_treeview').value = 1;
    $('#full-size-form').submit();
}