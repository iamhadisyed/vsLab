<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/30/15
 * Time: 7:50 PM
 */
namespace App\Http\Controllers;

use Redirect;
use Request;
use Response;
use MongoDB;

class LabTreeController extends BaseController
{
    protected $user;
    protected $jstree_json;

    //public $restful=true;

    public function __construct()
    {
        if (\Cas::isAuthenticated()) {
            $this->user = \Cas::getCurrentUser();

            //$this->cloudRes = App::make('CloudResource');
        } else {
            return Redirect::to('/');
        }
    }

    function getTreeList()
    {
        if (Request::ajax()) {
            $courseid = 'SS02';

            $name_id = '2015_T1';
            set_error_handler(function () {
            });
            $insert = array("_id" => array("name" => "root", "course" => "root"));
            //print_r($insert);
            $newtree = new Node($insert); // root node
            createBaseTree($newtree);
            //displayTree($newtree);
            $treenode1 = treeSearch($newtree, $name_id, $courseid);
            global $jstree_str;

            $jstree_str = "<ul>\n";
            $jstree_str = $jstree_str . "<li> <a>" . $treenode1->value['metadata']['display_name'] . "</a><br>\n";
//                "&nbsp;&nbsp;<button class='btn_show_lab_topo' name='" . $treenode1->value['metadata']['display_name'] . "' value='lab_show_".$courseid."_".$name_id."'>Show Topology</button>&nbsp;<button class='btn_edit_lab' name='" . $treenode1->value['metadata']['display_name'] . "' value='lab_edit_".$courseid."_".$name_id."'>Edit</button></a><br>\n";
            $jstree_str = $jstree_str . displaySidebar($treenode1);

            $jstree_str = $jstree_str . "</li>\n";
            $jstree_str = $jstree_str . "</ul>\n";
            restore_error_handler();
            return Response::json($jstree_str);

        }
    }

    function getTreeContent($courseid,$name_id)
    {
        if (Request::ajax()) {

            set_error_handler(function () {
            });
            $insert = array("_id" => array("name" => "root", "course" => "root"));
            //print_r($insert);
            $newtree = new Node($insert); // root node
            createBaseTree($newtree);
            //displayTree($newtree);
            $treenode1 = treeSearch($newtree, $name_id, $courseid);
            global $jstree_str;

            $jstree_str = "<ul>\n";
            $jstree_str = $jstree_str . "<li> <a>" . $treenode1->value['metadata']['display_name'] . "</a><br>\n";
//            <button class='btn_show_lab_topo' name='" . $treenode1->value['metadata']['display_name'] . "' value='lab_show_".$courseid."_".$name_id."'>Show Topology</button>&nbsp;<button class='btn_edit_lab' name='" . $treenode1->value['metadata']['display_name'] . "' value='lab_edit_".$courseid."_".$name_id."'>Edit</button></a><br>\n";
            $jstree_str = $jstree_str . displaySidebar($treenode1,$name_id);

            $jstree_str = $jstree_str . "</li>\n";
            $jstree_str = $jstree_str . "</ul>\n";
            restore_error_handler();
            return Response::json($jstree_str);

        }
    }

    function getOpenTreeContent($courseid,$name_id)
    {
        if (Request::ajax()) {

            set_error_handler(function () {
            });
            $insert = array("_id" => array("name" => "root", "course" => "root"));
            //print_r($insert);
            $newtree = new Node($insert); // root node
            createBaseTree($newtree);
            //displayTree($newtree);
            $treenode1 = treeSearch($newtree, $name_id, $courseid);
            global $jstree_str;

            $jstree_str = "<ul>\n";
            $jstree_str = $jstree_str . "<li> <a>" . $treenode1->value['metadata']['display_name'] . "<button width='16' height='16' background-color='transparent' border='none' margin='2px' class='btn_show_lab_topo btn-tooltip' title='Show Topology' name='" . $treenode1->value['metadata']['display_name'] . " Topology' value='openlab_topo_".$courseid."_".$name_id."'><img src='https://www.thothlab.org/workspace-assets/images/icons/network-transmit.png' width='16' height='16' ></button></a><br>\n";
            $jstree_str = $jstree_str . displaySidebar($treenode1,$name_id);

            $jstree_str = $jstree_str . "</li>\n";
            $jstree_str = $jstree_str . "</ul>\n";
            restore_error_handler();
            return Response::json($jstree_str);

        }
    }
//    function displayContent($course_id,$name_id){
//        if (Request::ajax()) {
//            //echo "in display content function";
//            $m = new MongoClient("mongodb://192.168.2.224:27017");
//            $db = $m->edxapp;
//            $collection = $db->modulestore;
//            $query = array('$and' => array(array('_id.course' => $course_id), array('_id.name' => $name_id)));
//            $cursor = $collection->find($query);
//            //iterate through the results
//            $content_str = "";
//            foreach ($cursor as $document) {
//                // echo "calling function";
//                $content_str = $content_str . getContents($document, $course_id);
//
//            }
//            return Response::json($content_str);
//        }
//    }
    function getContent($courseid,$name_id)
    {
        if (Request::ajax()) {
            set_error_handler(function () {
            });
            $insert = array("_id" => array("name" => "root", "course" => "root"));
            //print_r($insert);
            $newtree = new Node($insert); // root node
            createBaseTree($newtree);
            //displayTree($newtree);
            $treenode1 = treeSearch($newtree, $name_id, $courseid);
            $jstree_str = "";
            $jstree_str = $jstree_str . displayContent($treenode1);
            restore_error_handler();
            return Response::json($jstree_str);
        }
    }
}

class Node
{
    public $value = array();    // contains the node item
    public $next = array();     // the left child BinaryNode
    public $parent;

    public function __construct($item) {
        $this->value = $item;
        // parent is set to null
        $this->parent = null;

    }
    // perform an in-order traversal of the current node
}
$jstree_str="";

function createBaseTree(&$treenode){
//    global $newtree;
    $m = new MongoDB\Driver\Manager("mongodb://10.2.0.15:27017");

//select database

    //$db = $m->edxapp ;

// select a collection

    //$collection = $db->modulestore;

// find in collection
    //$query = array('_id.category'=>'course');

    //$cursor = $collection->find($query);
    $query = new MongoDB\Driver\Query(array('_id.category'=>'course'));
    $cursor = $m->executeQuery('edxapp.modulestore',$query);
    $array = ($cursor->toArray());
//iterate through the results

    foreach ($array as $document) {
        $document = json_decode(json_encode($document),True);
        $temp = new Node($document);
        array_push($treenode->next,$temp);
        $temp->parent = $treenode;
        $_id = $document['_id'];
        $definition = $document['definition'];
        //print_r( $_id );
        insertTree($_id,$definition,$temp);
    }
}

function insertTree($value, $detail, &$treenode){
    $m = new MongoDB\Driver\Manager("mongodb://10.2.0.15:27017");
//select database

    //$db = $m->edxapp ;

// select a collection

    //$collection = $db->modulestore;

    try {
        set_error_handler(function(){});
        $root = $value['name'];
        //echo "\n" . $value['name'] ."(". $value['category'] .")". " \n";
        $children = $detail['children'];
        //$children_keys = array();
        $children_keys = array_keys($children);

        restore_error_handler();

    }
    catch (Exception $e){
        //array_push($leaf_nodes,$root);
        //echo "(leaf node)";
        return;
    }

    if(count($children_keys)>0) {
        $info_value = array();
        $children = array_unique($children);
        foreach ($children_keys as $key) {
//            if(in_array($children[$key],$info_value)){
//                echo $children[$key]."already exists \n";
//                continue;
//            }
//            else{
//                array_push($info_value,$children[$key]);
//            }

            $new_id = explode("/", $children[$key]);
            $index = count($new_id);

            $query = new MongoDB\Driver\Query(array('$and' => array(array('_id.course' => $value['course']), array('_id.name' => $new_id[$index - 1]))));
            $cursor = $m->executeQuery('edxapp.modulestore',$query);
            $array = ($cursor->toArray());
            foreach ($array as $document) {
                $document = json_decode(json_encode($document),True);
                $temp = new Node($document);
                array_push($treenode->next,$temp);
                $temp->parent = $treenode;
                $_id1 = $document['_id'];
                $definition1 = $document['definition'];
                //print_r( $_id1 );
                insertTree($_id1, $definition1, $temp);
            }
        }
    }
    else
    {
        //array_push($leaf_nodes,$root);
        //echo "(leaf node)";
        return;
    }
}

function displayTree(&$treenode){
    // print_r($treenode->value);
    set_error_handler(function(){});
    echo "Child: "."course : ".$treenode->value['_id']['course']."  name : ".$treenode->value['_id']['name']."\n";
    echo "Parent "."course : ".$treenode->parent->value['_id']['course']."  name : ".$treenode->parent->value['_id']['name']."\n";
    restore_error_handler();
    for($i=0;$i<count(array_keys($treenode->next));$i++){
        displayTree($treenode->next[$i]);
    }
}

function treeSearch(&$treenode,$name,$course){
    if($treenode->value['_id']['name']==$name && $treenode->value['_id']['course']==$course){
        //print_r($treenode->value['_id']);
        return $treenode;
    }
    else{
        if(count(array_keys($treenode->next))>0){
            for($i=0;$i<count(array_keys($treenode->next));$i++){
                $searchednode =treeSearch($treenode->next[$i],$name,$course);
                if($searchednode->value['_id']['name']==$name && $searchednode->value['_id']['course']==$course){
                    return $searchednode;
                }
            }

        }
    }

}
function displaySidebar(&$treenode,$name_id){
    //echo "inside function";
    $jstree_str="";
    $children_count = count(array_keys($treenode->next));

    if($children_count>=0){
        $jstree_str=$jstree_str."<ul>\n";
        for ($i=0;$i<$children_count;$i++){
            $temp_node = $treenode->next[$i];
            $courseid=$temp_node->value['_id']['course'];
            $courseid = str_replace('_','',$courseid);
            if($temp_node->value['_id']['category']=='html' || $temp_node->value['_id']['category']=='video' || $temp_node->value['_id']['category']=='problem' || $temp_node->value['_id']['category']=='discussion'){
                continue;
            }elseif($temp_node->next[0]->value['_id']['category']=='html' || $temp_node->next[0]->value['_id']['category']=='video' || $temp_node->next[0]->value['_id']['category']=='problem' || $temp_node->next[0]->value['_id']['category']=='discussion'){
                //echo "<li name='_id' value='".$temp_node->value['_id']['course']."+".$temp_node->value['_id']['name']."'> <a>".$temp_node->value['metadata']['display_name']."</a> \n";
                $jstree_str=$jstree_str."<li> <a><button class='link treebutton' onclick=scrollToRightDiv('right_pane_".$courseid."_".$name_id."','".$temp_node->value['_id']['course']."_".$temp_node->value['_id']['name']. "')>" .$temp_node->value['metadata']['display_name']. "</button></a><br>\n";
                $jstree_str=$jstree_str.displaySidebar($temp_node,$name_id);
                $jstree_str=$jstree_str."</li>";
            }else{
                $jstree_str=$jstree_str."<li> <a><button class='link treebutton' onclick=scrollToRightDiv('right_pane_".$courseid."_".$name_id."','".$temp_node->value['_id']['course']."_".$temp_node->value['_id']['name']. "')>" .$temp_node->value['metadata']['display_name']. "</button></a><br>\n";
                $jstree_str=$jstree_str.displaySidebar($temp_node,$name_id);
                $jstree_str=$jstree_str."</li>";
            }
        }
        $jstree_str=$jstree_str."</ul>\n";
        return $jstree_str;

    }
    else{

        return "";
    }
}

function displayContent(&$treenode){

    $data_str="";
    $children_count = count(array_keys($treenode->next));
    if($children_count>=0){

        for ($i=0;$i<$children_count;$i++){
            $temp_node = $treenode->next[$i];
            if($temp_node->value['_id']['category']=='html' || $temp_node->value['_id']['category']=='video' || $temp_node->value['_id']['category']=='problem' || $temp_node->value['_id']['category']=='discussion'){

                if($temp_node->value['_id']['category']=='html'){

                    $def1=$temp_node->value['definition']['data'];
                    $data = $def1['data'];
                    $data_str=$data_str. "<div class='lab_content' id='".$temp_node->value['_id']['course']."_".$temp_node->value['_id']['name']."'> \n";
                    //echo $data."----1\n";
//                    if(strpos($data,'.pdf')!== false){
//                        //echo strpos($data,'.pdf');
//                        //echo "pdf";
//                        //echo $data."----2\n";
//                        //echo strpos($data,'/static');
//                        $strtpos1 = strpos($data,'/static');
//                        if($strtpos1!==false){
//                            $replace_str = 'http://learning.mobicloud.asu.edu/c4x/'.$temp_node->value['_id']['org'].'/'.$temp_node->value['_id']['course'].'/asset';
//                            //echo $replace_str;
//                            $data= substr_replace($data,$replace_str,$strtpos1,7);
//                            //echo $data;
//                        }
//
//                    }
                    $pos_start = strpos($data,'http://learning.mobicloud');
                    $pos_end = strpos($data,'.pdf');
                    $pos_end = $pos_end+4;
                    $pdf_id = substr($data,$pos_start,$pos_end-$pos_start);
                    //echo $pdf_id;
                    $data_str=$data_str. $data."\n";
//                    $data_str=$data_str. "<div id='container-2'> \n";
//                    $data_str=$data_str. "<div class='viewer' id='viewer'> \n";
//                    $data_str=$data_str. "<embed id='pdf1' type='application/pdf' width='500' height='600' src='".$pdf_id."'/>\n";
//                    //echo "<script type='text/javascript'> loadPDF('".$pdf_id."')  </script>\n";
//                    $data_str=$data_str. "\n</div> \n</div> \n";
                    $data_str=$data_str. "</div> \n";
                    $data_str=str_replace('labstudio.mobicloud.asu.edu','labstudio.thothlab.org',$data_str);
                }elseif($temp_node->value['_id']['category']=='video'){
                    //echo "video";
                    $flag1=1;
                    //echo $flag1;

                    $ytube_id = $temp_node->value['metadata']['youtube_id_1_0'];
                    // echo $ytube_id;
                    $data_str=$data_str. "<div class='lab_content' id='".$temp_node->value['_id']['course']."_".$temp_node->value['_id']['name']."'> \n";
                    $data_str=$data_str. "<div id='container-2'> \n";
                    $data_str=$data_str. "<div class='viewer' id='viewer'> \n";
                    $data_str=$data_str. "<iframe id='ytplayer1' type='text/html' width='640' height='390' frameborder='0' src='https://www.youtube.com/embed/". $ytube_id ."?enablejsapi=1&origin=http://example.com'/>\n";
                    //echo "<script type='text/javascript'> loadVideo('".$ytube_id."')  </script>\n";
                    $data_str=$data_str. "\n</div> \n</div> \n</div> \n";

                }
                elseif($temp_node->value['_id']['category']=='problem'){
                    $data_str=$data_str. "Problem : \n";
                    $flag1=1;
                    // echo $flag1;

                    $data_name = $temp_node->value['metadata']['display_name'];
                    $data_str=$data_str. "<div class='lab_content' id='".$temp_node->value['_id']['course']."_".$temp_node->value['_id']['name']."'> \n";
                    $data_str=$data_str. "<div id='newp1'> \n";
                    $data_str=$data_str. $data_name."\n\n The XML files must be displayed";
                    $data_str=$data_str. "</div>\n</div> \n";
                }elseif($temp_node->value['_id']['category']=='discussion'){
                    $data_str=$data_str. "Discussion : \n";
                    $flag1=1;
                    $data_str=$data_str. "\n\n The XML files must be displayed";
                    $def1=$temp_node->value['definition']['data'];
                    $data = $def1['data'];
                    $data_str=$data_str. "<div class='lab_content' id='".$temp_node->value['_id']['course']."_".$temp_node->value['_id']['name']."'> \n";

                    $data_str=$data_str. "<div id='newp1'> \n";
                    $data_str=$data_str. $data."\n";
                    $data_str=$data_str. "</div> \n</div>\n";
                }
            }else{
                $data_str=$data_str."<div class=\"lab_content_heading\"id=\"" .$temp_node->value['_id']['course']."_".$temp_node->value['_id']['name']. "\">" .$temp_node->value['metadata']['display_name']. " <br/><br/>\n";
                $data_str=$data_str.displayContent($temp_node);
                $data_str=$data_str."</div>";
            }
        }

        return $data_str;

    }
    else{

        return "";
    }
}
