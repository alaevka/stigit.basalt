(function ($, undefined) {
	"use strict";

	$.jstree.defaults.actions = $.noop;

	$.jstree.plugins.actions = function (options, parent) {

		this._actions = {};

		/**
		 * @param node_id Can be a single node id or an array of node ids.
		 * @param action An object representing the action that should be added to <node>.
		 *
		 * The <node id> is the "id" key of each element of the "core.data" array.
		 * A special value "all" is allowed, in which case the action will be added to all nodes.
		 *
		 * The actions object can contain the following keys:
		 * id       <- string An ID which identifies the action. The same ID can be shared across different nodes
		 * text     <- string The action's text
		 * class    <- string (a string containing all the classes you want to add to the action (space separated)
		 * selector <- a selector that would specify where to insert the action. Note that this is a plain JavaScript selector and not a jQuery one.
		 * after    <- bool (insert the action after (true) or before (false) the element matching the <selector> key
		 * event    <- string The event on which the trigger will be called
		 * callback <- function that will be called when the action is clicked
		 *
		 * NOTES: Please keep in mind that:
		 * - the id's are strictly compared (===)
		 * - the selector has access to all children on nodes with leafs/children, so most probably you'd want to use :first or similar
		 */
		this.add_action = function (node_id, action) {

			var self = this;
			node_id = typeof node_id === Object ? node_id : [node_id];

			for (var i = 0; i < node_id.length; i++) {
				var _node_id = node_id[i];
				var actions = self._actions[_node_id] = self._actions[_node_id] || [];

				if (!self._has_action(_node_id, action.id)) actions.push(action);
			}

			//TODO: Redraw only the modified nodes?
			this.redraw(true);
		};

		/**
		 * @param node_id Can be a single node id or an array of node ids
		 * @param action_id The ID of the action to be removed
		 *
		 * The <node id> is the "id" key of each element of the "core.data" array.
		 * A special value "all" is allowed, in which case the action_id will be removed from all nodes.
		 *
		 * The action_id is the unique identifier for each action.
		 * A special value "all" is allowed, in which case all the actions of node_id will be removed.
		 */
		this.remove_action = function (node_id, action_id) {
			var self = this;
			var node_ids = typeof node_id === Object ? node_id :
				node_id === "all" ? Object.keys(this._actions).concat('all') :
					[node_id];



			for (var i = 0; i < node_ids.length; i++) {
				node_id = node_ids[i];
				var actions = self._actions[node_id] || [];
				var new_actions = [];

				for (var j = 0; j < actions.length; j++) {
					var action = actions[j];
					if(action.id !== action_id && action_id !== "all") {
						new_actions.push(action);
					}
				}
				self._actions[node_id] = new_actions;
			}

			//TODO: Redraw only the modified nodes?
			this.redraw(true);
		};

		this._create_action = function (node_id, action_id) {
			
			var self = this;
			var action = this._get_action(node_id, action_id);
			if (action === null) return null;

			var action_el = document.createElement("i");
			action_el.className = action.class;
			action_el.textContent = action.text;
			action_el.onclick = function() {
				var node = self.get_node(action_el);
				action.callback(node_id, node, action_id, action_el);
			};

			return {
				"action": action,
				"action_el": action_el
			};
		};

		this._get_action = function (node_id, action_id) {
			var actions = this._actions[node_id] || [];
			var v = null;
			for (var i = 0; i < actions.length; i++) {
				var action = actions[i];
				if (action.id === action_id) {
					//TODO: fill empty fields with default values?
					v = action;
				}
			}
			return v;
		};

		this._set_action = function (node_id, obj, action) {
			if (action === null) return;

			var place = obj.querySelector(action.action.selector);
			if (action.action.after) {
				place.parentNode.insertBefore(action.action_el, place.nextSibling);
			} else {
				obj.insertBefore(action.action_el, place);
			}
		};

		this._has_action = function (node_id, action_id) {
			var found = false;
			var actions = this._actions;

			if (actions.hasOwnProperty(node_id)) {
				for (var i = 0; i < actions[node_id].length; i++) {
					if (actions[node_id][i].id === action_id) found = true;
				}
			}

			if (this._actions.hasOwnProperty('all')) {
				for (i = 0; i < actions['all'].length; i++) {
					if (actions['all'][i].id === action_id) found = true;
				}
			}

			return found;
		};

		this.redraw_node = function (obj, deep, callback, force_draw) {
			var self = this;
			var node_id = obj;


			var el = parent.redraw_node.call(this, obj, deep, callback, force_draw);
			if (el) {
				//Check if we have any specific actions for this node
				var actions = this._actions[node_id] || [];

				for (var i = 0; i < actions.length; i++) {

					var _action = self._create_action(node_id, actions[i].id);
					self._set_action(node_id, el, _action);
				}

				actions = this._actions["all"] || [];

				for (i = 0; i < actions.length; i++) {
					_action = self._create_action("all", actions[i].id);
					self._set_action(node_id, el, _action);
				}
			}
			return el;
		};

	}

})(jQuery);